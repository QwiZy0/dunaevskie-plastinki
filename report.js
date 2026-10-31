/* ====================================================
   report.js — таблица отчёта (jQuery + AJAX)
   Берёт данные у report.php?data=1, рисует таблицу,
   сортирует по клику на заголовок без перезагрузки страницы.
   ==================================================== */

$(document).ready(function () {
  // Текущие настройки отчёта
  const state = { group: "session", sort: "bytes", dir: "desc" };

  // Какой столбец главный («рейтинг») в каждом режиме
  const MAIN_COLUMN = { session: "bytes", period: "total_bytes" };

  const BYTE_COLUMNS = ["bytes", "total_bytes", "avg_bytes", "max_bytes"];
  const NUMBER_COLUMNS = ["hits", "sessions"];
  const TEXT_COLUMNS = ["ip", "per", "start_time"];

  function formatBytes(n) {
    const units = ["Б", "КБ", "МБ", "ГБ"];
    n = Number(n);
    let i = 0;
    while (n >= 1024 && i < units.length - 1) {
      n /= 1024;
      i++;
    }
    return (i === 0 ? n : n.toFixed(1)) + " " + units[i];
  }

  function formatDuration(seconds) {
    seconds = Number(seconds);
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;
    return (h ? h + " ч " : "") + (m ? m + " мин " : "") + s + " с";
  }

  // Режим «по сессиям» или «по периодам» зависит от выбранной группировки
  function currentMode() {
    return state.group === "session" ? "session" : "period";
  }

  function loadReport() {
    $("#report-status").text("Загрузка…");

    $.getJSON("report.php", { data: 1, group: state.group, sort: state.sort, dir: state.dir })
      .done(render)
      .fail(function (xhr) {
        const text = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : "Не удалось получить данные.";
        $("#report-status").text(text);
      });
  }

  function render(answer) {
    if (!answer.ok) {
      $("#report-status").text(answer.error);
      return;
    }

    state.sort = answer.sort;
    state.dir = answer.dir;

    // ----- Заголовок: столбцы с подсказкой направления сортировки -----
    const $head = $("<tr>");
    answer.columns.forEach(function (col) {
      const $th = $("<th>").addClass("sortable").attr("data-key", col.key).text(col.label);
      if (col.key === state.sort) {
        $th.addClass(state.dir);
      }
      $head.append($th);
    });
    $("#report-table thead").empty().append($head);

    // ----- Строки -----
    const main = MAIN_COLUMN[answer.mode];
    const max = Math.max.apply(null, answer.rows.map(function (r) { return Number(r[main]); }).concat([1]));
    const $body = $("#report-table tbody").empty();

    answer.rows.forEach(function (row) {
      const $tr = $("<tr>");
      answer.columns.forEach(function (col) {
        const value = row[col.key];
        const $td = $("<td>");

        if (col.key === "duration") {
          $td.text(formatDuration(value));
        } else if (BYTE_COLUMNS.indexOf(col.key) !== -1) {
          $td.text(formatBytes(value));
        } else if (NUMBER_COLUMNS.indexOf(col.key) !== -1) {
          $td.text(Number(value).toLocaleString("ru-RU"));
        } else {
          $td.text(value);      // .text() — безопасно, HTML из данных не выполнится
        }

        // Полоска рядом с главным столбцом показывает долю от максимума
        if (col.key === main) {
          $td.append($("<span>").addClass("bar").append($("<i>").css("width", (Number(value) / max * 100) + "%")));
        }
        $tr.append($td);
      });
      $body.append($tr);
    });

    $("#report-status").text(
      answer.rows.length ? "Строк в отчёте: " + answer.rows.length : "В таблице logs пока нет данных. Сначала загрузите лог."
    );
  }

  // Клик по заголовку: тот же столбец меняет направление, новый начинается с «по убыванию»
  // (текстовые столбцы начинаются «по возрастанию»)
  $("#report-table").on("click", "th.sortable", function () {
    const key = $(this).data("key");

    if (state.sort === key) {
      state.dir = state.dir === "asc" ? "desc" : "asc";
    } else {
      state.sort = key;
      state.dir = TEXT_COLUMNS.indexOf(key) !== -1 ? "asc" : "desc";
    }
    loadReport();
  });

  // Смена группировки (сессии, часы, дни, недели, месяцы)
  $("#group").on("change", function () {
    state.group = $(this).val();
    state.sort = MAIN_COLUMN[currentMode()];
    state.dir = "desc";
    loadReport();
  });

  // Рейтинг: от большего к меньшему и наоборот по главному столбцу
  $("#btn-top").on("click", function () {
    state.sort = MAIN_COLUMN[currentMode()];
    state.dir = "desc";
    loadReport();
  });

  $("#btn-bottom").on("click", function () {
    state.sort = MAIN_COLUMN[currentMode()];
    state.dir = "asc";
    loadReport();
  });

  loadReport();
});
