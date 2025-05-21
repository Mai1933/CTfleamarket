
const body = document.body;
const modal = document.querySelector("dialog");
const dialogOpenButtons = document.querySelectorAll(".partner_complete");
dialogOpenButtons.forEach((button) => {
    const dialog = document.querySelector(button.dataset.dialog);
    button.addEventListener("click", () => {
        const isStock = button.dataset.isStock === 'true';
        if (isStock) {
            return;
        }

        dialog.showModal();
        body.style.overflow = "hidden";
});
});

// const completeButton = document.querySelector('.partner_complete'); // 取引完了ボタン
// const message = document.getElementById('evaluation-message'); // メッセージ

// completeButton.addEventListener("click", () => {
//     if ($item->status === 'stock')
//         message.style.display = 'block'; // 表示
//     endif
// });

const modals = document.querySelectorAll(".dialog_open-inner");
modals.forEach((modal) => {
modal.addEventListener("click", (event) => {
    if (!event.target.closest("form") && !event.target.closest("button")) {
    modal.close();
    document.body.style.overflow = "visible";
    }
});
});

modals.forEach((modal) => {
modal.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
    body.style.overflow = "visible";
    }
});
});