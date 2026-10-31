
/* ---------- 1. ВЕРСИЯ ДЛЯ ПЕЧАТИ ---------- */

const NORMAL_CSS = "styles.css";   // обычное оформление
const PRINT_CSS = "print.css";     // версия для печати

// Узнаём, какой режим выбран. localStorage хранит выбор в браузере,
// поэтому при переходе на другую страницу режим не сбрасывается.
function getView() {
  try {
    return localStorage.getItem("view") === "print" ? "print" : "normal";
  } catch (e) {
    return "normal";
  }
}

// Включаем нужный режим: меняем href у тега <link id="main-style">
function setView(mode) {
  $("#main-style").attr("href", mode === "print" ? PRINT_CSS : NORMAL_CSS);
  $("#print-btn").text(mode === "print" ? "Обычная версия" : "Версия для печати");
  try {
    localStorage.setItem("view", mode);
  } catch (e) {}
}

// Применяем сохранённый режим сразу при загрузке страницы
setView(getView());


/* ---------- 2. УМНЫЙ ФИЛЬТР КАТАЛОГА ---------- */

// Характеристики, по которым фильтруем.
// Ключ = имя data-атрибута у карточки (data-genre, data-decade...)
const properties = {
  genre: "Жанр",
  decade: "Десятилетие",
  status: "Наличие",
  artist: "Исполнитель"
};

// Порядок значений там, где алфавит не подходит
const order = {
  decade: ["90-е", "2000-е", "2010-е", "2020-е"],
  status: ["В наличии", "Осталось 1–2", "Предзаказ"]
};

// Только карточки пластинок (плашка «Не нашли свою?» в фильтре не участвует)
const CARDS = ".card[data-genre]";


// Собирает чекбоксы из значений, которые есть у карточек (шаблонизатор)
function printFilters(selector) {
  let output = "";

  for (let prop in properties) {
    // 1) собираем все уникальные значения характеристики
    let values = [];
    $(CARDS).each(function () {
      const value = $(this).attr("data-" + prop);
      if (!values.includes(value)) {
        values.push(value);
      }
    });

    // 2) сортируем
    if (order[prop]) {
      values.sort((a, b) => order[prop].indexOf(a) - order[prop].indexOf(b));
    } else {
      values.sort((a, b) => a.localeCompare(b, "ru"));
    }

    // 3) подставляем значения в HTML-шаблон
    output += `<fieldset class="filter-group">
      <legend>${properties[prop]}</legend>`;
    for (let value of values) {
      output += `
      <label class="filter-item">
        <input type="checkbox" name="${prop}" value="${value}">
        <span>${value}</span>
        <span class="count"></span>
      </label>`;
    }
    output += `</fieldset>`;
  }

  $(selector).html(output);
}


// Читает, какие галочки сейчас стоят. Результат, например:
// { genre: ["Эмо"], decade: [], status: ["Предзаказ"], artist: [] }
function readFilters() {
  const result = {};
  for (let prop in properties) {
    result[prop] = $("#filters input[name='" + prop + "']:checked")
      .map(function () {
        return this.value;
      })
      .get();
  }
  return result;
}


// Подходит ли карточка под выбранные галочки?
// skipProp — характеристика, которую пропускаем (нужно для «умной» части)
function matches(card, filters, skipProp) {
  for (let prop in properties) {
    if (prop === skipProp) continue;               // эту группу не проверяем
    if (filters[prop].length === 0) continue;      // в группе ничего не выбрано
    if (!filters[prop].includes($(card).attr("data-" + prop))) {
      return false;                                // значение не выбрано
    }
  }
  return true;
}


// Главная функция: вызывается при каждом клике по галочке
function updateFilter() {
  const filters = readFilters();

  // --- Обычный фильтр: показываем и скрываем карточки ---
  let found = 0;
  $(CARDS).each(function () {
    const visible = matches(this, filters, null);
    $(this).toggle(visible);
    if (visible) found++;
  });
  $("#found-count").text(found);
  $("#empty-message").toggle(found === 0);

  // --- «Умная» часть: серим галочки, которые дадут пустой результат ---
  $("#filters input").each(function () {
    const prop = this.name;
    const value = this.value;

    // Считаем карточки с этим значением, которые подходят под ДРУГИЕ группы
    let count = 0;
    $(CARDS).each(function () {
      if ($(this).attr("data-" + prop) === value && matches(this, filters, prop)) {
        count++;
      }
    });

    // Нет таких карточек, и галочка не стоит — делаем её недоступной
    const unavailable = (count === 0 && !this.checked);
    $(this).prop("disabled", unavailable);
    $(this).closest("label").toggleClass("disabled", unavailable);
    $(this).siblings(".count").text(count);
  });
}


function initFilter() {
  printFilters("#filters");
  $("#total-count").text($(CARDS).length);

  // Галочки созданы скриптом, поэтому обработчик вешаем на контейнер
  $("#filters").on("change", "input", updateFilter);

  $("#reset-filters").on("click", function () {
    $("#filters input").prop("checked", false);
    updateFilter();
  });

  updateFilter();
}


/* ---------- Запуск после загрузки страницы ---------- */
$(document).ready(function () {
  setView(getView());   // теперь кнопка уже есть на странице — обновим её текст

  $("#print-btn").on("click", function () {
    setView(getView() === "print" ? "normal" : "print");
  });

  if ($("#filters").length) {   // фильтр есть только в каталоге
    initFilter();
  }
});
