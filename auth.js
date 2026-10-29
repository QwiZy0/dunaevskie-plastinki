/* ====================================================
   auth.js — всплывающее окно входа и регистрации (jQuery)
   Формы отправляются через AJAX, страница не перезагружается.
   ==================================================== */

$(document).ready(function () {
  const $modal = $("#auth-modal");

  // Заголовки окна для каждой вкладки
  const TEXTS = {
    login: ["С возвращением", "Войдите, чтобы заказывать пластинки"],
    register: ["Создать аккаунт", "Займёт минуту, и вы в личном кабинете"],
    forgot: ["Забыли пароль?", "Пришлём ссылку для смены пароля"]
  };
  const $msg = $("#form-msg");

  // ----- Показать вкладку: login, register или forgot -----
  function showTab(name) {
    $(".modal-form").removeClass("active");
    $("#form-" + name).addClass("active");

    $(".modal-tabs button").removeClass("active");
    $(".modal-tabs button[data-tab='" + name + "']").addClass("active");

    $modal.toggleClass("forgot", name === "forgot");   // на вкладке «Забыли пароль» вкладки скрыты
    $("#modal-title").text(TEXTS[name][0]);
    $("#modal-sub").text(TEXTS[name][1]);
    $msg.text("").removeClass("error ok");
    $("#form-" + name + " input:visible:first").trigger("focus");
  }

  function openModal(tab) {
    $modal.addClass("open").attr("aria-hidden", "false");
    $("body").addClass("modal-open");
    showTab(tab || "login");
  }

  function closeModal() {
    $modal.removeClass("open").attr("aria-hidden", "true");
    $("body").removeClass("modal-open");
  }

  // ----- Открытие и закрытие -----
  $("[data-modal]").on("click", function () {
    openModal($(this).data("modal"));
  });

  $(".modal-close").on("click", closeModal);

  $modal.on("click", function (event) {
    if (event.target === this) {      // клик по тёмному фону вокруг окна
      closeModal();
    }
  });

  $(document).on("keydown", function (event) {
    if (event.key === "Escape") {
      closeModal();
    }
  });

  // ----- Переключение вкладок и ссылок внутри окна -----
  $(".modal-tabs button").on("click", function () {
    showTab($(this).data("tab"));
  });

  $("[data-tab-link]").on("click", function (event) {
    event.preventDefault();
    showTab($(this).data("tab-link"));
  });

  // ----- Отправка форм без перезагрузки -----
  $(".modal-form").on("submit", function (event) {
    event.preventDefault();

    const $button = $(this).find("button[type='submit']").prop("disabled", true);

    $.ajax({
      url: $(this).attr("action"),
      method: "POST",
      data: new FormData(this),     // вместе с файлом аватара
      processData: false,
      contentType: false,
      dataType: "json"
    })
      .done(function (answer) {
        if (answer.ok && answer.redirect) {
          window.location.href = answer.redirect;     // вошли: идём в личный кабинет
        } else if (answer.ok) {
          showMessage(answer.message, "ok", answer.link);
        } else {
          showMessage(answer.error, "error");
        }
      })
      .fail(function () {
        showMessage("Ошибка сервера. Попробуйте позже.", "error");
      })
      .always(function () {
        $button.prop("disabled", false);
      });
  });

  // Сообщение выводим через .text(): так пользовательский текст не станет HTML (защита от XSS)
  function showMessage(text, type, link) {
    $msg.removeClass("error ok").addClass(type).text(text);
    if (link) {
      $msg.append("<br>").append($("<a>").attr("href", link).text(link));
    }
  }

  // ----- Кнопка «Показать / Скрыть пароль» -----
  $(".pw-toggle").on("click", function () {
    const $input = $(this).siblings("input");
    const show = $input.attr("type") === "password";
    $input.attr("type", show ? "text" : "password");
    $(this).text(show ? "Скрыть" : "Показать");
  });

  // ----- Шкала надёжности пароля (те же правила, что и на сервере) -----
  $("input[data-strength]").on("input", function () {
    const value = $(this).val();
    const length = Array.from(value).length;
    const $box = $(this).closest("form").find(".strength");

    const rules = {
      len: length >= 10,
      lower: /\p{Ll}/u.test(value),
      upper: /\p{Lu}/u.test(value),
      digit: /\d/.test(value),
      special: /[^\p{L}\d]/u.test(value)
    };

    let score = 0;
    $.each(rules, function (name, ok) {
      $box.find("[data-rule='" + name + "']").toggleClass("ok", ok);
      if (ok) score++;
    });
    if (score === 5 && length >= 14) score = 6;      // длинный пароль — бонус

    const names = ["", "Очень слабый", "Слабый", "Слабый", "Средний", "Хороший", "Отличный"];
    const level = value === "" ? 0 : Math.min(4, Math.ceil(score * 4 / 6));
    $box.attr("class", "strength lvl-" + level);
    $box.find("i").css("width", (score / 6 * 100) + "%");
    $box.find(".strength-text").text(value === "" ? "Надёжность пароля" : names[score] || "Очень слабый");
  });

  // ----- Окно открывается само, если нас отправили на вход (?login=1) -----
  const match = window.location.search.match(/[?&]login=(\w+)/);
  if (match && $modal.length) {
    openModal(["register", "forgot"].includes(match[1]) ? match[1] : "login");
  }
});
