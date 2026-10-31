/* ====================================================
   logs.js — загрузчик лог-файла (jQuery + AJAX)
   Скрипт по очереди просит сервер (ajax_load.php) загрузить
   следующую порцию и рисует прогресс-бар.
   ==================================================== */

$(document).ready(function () {
  const $loader = $("#loader");
  const CSRF = $loader.data("csrf");

  let offset = 0;          // с какого байта продолжать чтение
  let fileSize = Number($loader.data("size"));
  let rowsLoaded = 0;      // сколько строк записано за эту загрузку
  let running = false;
  let finished = false;
  let startedAt = 0;

  $("#file-size").text(formatBytes(fileSize));

  // Размер в удобном виде: 1536 → «1.5 КБ»
  function formatBytes(n) {
    const units = ["Б", "КБ", "МБ", "ГБ"];
    let i = 0;
    while (n >= 1024 && i < units.length - 1) {
      n /= 1024;
      i++;
    }
    return (i === 0 ? n : n.toFixed(1)) + " " + units[i];
  }

  function showError(text) {
    $("#loader-error").text(text).show();
  }

  function setRunning(value) {
    running = value;
    $("#btn-start, #btn-clear").prop("disabled", value);
    $("#btn-stop").prop("disabled", !value);
  }

  // Обновляем полосу и подписи
  function updateProgress(percent) {
    $("#bar").css("width", percent + "%");
    $("#percent").text(percent + "%");
    $(".progress").attr("aria-valuenow", percent);

    const seconds = (Date.now() - startedAt) / 1000;
    const speed = seconds > 0 ? offset / 1048576 / seconds : 0;
    $("#stats").text(
      "Прочитано " + formatBytes(offset) + " из " + formatBytes(fileSize) +
      " · записано строк: " + rowsLoaded.toLocaleString("ru-RU") +
      " · " + seconds.toFixed(1) + " с (" + speed.toFixed(1) + " МБ/с)"
    );
  }

  // Один шаг: запрос порции. После ответа вызываем себя снова, пока не конец файла.
  function step() {
    if (!running) {
      return;       // нажали «Остановить»
    }

    $.post("ajax_load.php", { action: "load", offset: offset, csrf: CSRF }, null, "json")
      .done(function (answer) {
        if (!answer.ok) {
          showError(answer.error);
          setRunning(false);
          return;
        }

        offset = answer.offset;
        fileSize = answer.size;
        rowsLoaded += answer.rows;
        updateProgress(answer.percent);

        if (answer.done) {
          finished = true;
          setRunning(false);
          $("#btn-start").text("Загрузить заново");
          $("#stats").append(" · готово!");
          refreshCount();
        } else {
          step();
        }
      })
      .fail(function () {
        showError("Сервер не ответил. Проверьте, что Apache и MySQL запущены.");
        setRunning(false);
      });
  }

  // Сколько строк сейчас в таблице logs (спрашиваем у сервера)
  function refreshCount() {
    $.post("ajax_load.php", { action: "count", csrf: CSRF }, null, "json").done(function (answer) {
      if (answer.ok) {
        $("#rows-in-db").text(Number(answer.count).toLocaleString("ru-RU"));
      }
    });
  }

  function start() {
    $("#loader-error").hide();

    if (finished) {
      // Файл уже загружен целиком: заново — значит сначала очистить таблицу
      if (!confirm("Таблица logs будет очищена и лог загрузится заново. Продолжить?")) {
        return;
      }
      $.post("ajax_load.php", { action: "reset", csrf: CSRF }, null, "json").done(function () {
        offset = 0;
        rowsLoaded = 0;
        finished = false;
        begin();
      });
    } else {
      begin();
    }
  }

  function begin() {
    startedAt = Date.now();
    setRunning(true);
    step();
  }

  $("#btn-start").on("click", start);

  $("#btn-stop").on("click", function () {
    setRunning(false);          // следующий шаг не начнётся; offset запомнен
    $("#btn-start").text("Продолжить");
  });

  $("#btn-clear").on("click", function () {
    if (!confirm("Удалить все строки из таблицы logs?")) {
      return;
    }
    $.post("ajax_load.php", { action: "reset", csrf: CSRF }, null, "json").done(function (answer) {
      if (answer.ok) {
        offset = 0;
        rowsLoaded = 0;
        finished = false;
        $("#rows-in-db").text("0");
        $("#btn-start").text("Загрузить лог");
        updateProgress(0);
        $("#stats").text("Таблица очищена.");
      } else {
        showError(answer.error);
      }
    });
  });
});
