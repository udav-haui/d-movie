$(document).on("click", ".accordion-item__line", function () {
  var container = $(this).parents(".accordion-list__item");
  var answer = container.find(".accordion-item");
  answer.toggleClass("accordion-item--opened");
});
