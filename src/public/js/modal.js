
const body = document.body;
// ダイアログ要素
const modal = document.querySelector("dialog");
// ダイアログを開くボタン（水色）
const dialogOpenButtons = document.querySelectorAll(".partner_complete");
// ダイアログを閉じるボタン（ピンク）
// const dialogCloseButtons = document.querySelectorAll(".js-dialog-close");
// 水色のボタンが押されたら、ダイアログを開く。背景をスクロールさせない
dialogOpenButtons.forEach((button) => {
    const dialog = document.querySelector(button.dataset.dialog);
    button.addEventListener("click", () => {
        dialog.showModal();
        body.style.overflow = "hidden";
});
});
// 閉じるボタンでダイアログを閉じる、背景をスクロールさせられるようにする
// dialogCloseButtons.forEach((button) => {
//   const dialog = button.closest("dialog");
//   button.addEventListener("click", () => {
//     dialog.close();
//     body.style.overflow = "visible";
//   });
// });
// モーダルの外の黒い部分を押されたらダイアログを閉じる、背景をスクロールさせられるようにする
const modals = document.querySelectorAll(".dialog_open-inner");
modals.forEach((modal) => {
modal.addEventListener("click", (event) => {
    if (!event.target.closest("form") && !event.target.closest("button")) {
    modal.close();
    document.body.style.overflow = "visible";
    }
});
});

// // エスケープキーを押されたら背景をスクロールさせられるようにする
modals.forEach((modal) => {
modal.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
    body.style.overflow = "visible";
    }
});
});