/* ===============================
   ELEMENT REFERENCES
================================ */
const incomeBTN   = document.querySelector(".incomes-btn");
const expencesBTN = document.querySelector(".expenses-btn");

const incomeForm   = document.getElementById("incomes");
const expencesForm = document.getElementById("expences");

const contInc = document.getElementById("cont_inc");
const contExp = document.getElementById("cont_exp");

const logoutBtn = document.getElementById("logout-btn");
const logoutModal = document.getElementById("logout-modal");
const cancelLogoutBtn = document.getElementById("cancel-logout");
const logoutBg = document.getElementById("logout-bg");



// ===============================
// LOGOUT MODAL
// ===============================
logoutBtn?.addEventListener("click", () => {
  logoutModal.classList.remove("hidden");
});

cancelLogoutBtn?.addEventListener("click", () => {
  logoutModal.classList.add("hidden");
});

logoutBg?.addEventListener("click", () => {
  logoutModal.classList.add("hidden");
});

document.querySelector("#logout-modal .cont")?.addEventListener("click", e => {
  e.stopPropagation();
});

/* ===============================
   OPEN ADD MODALS
================================ */
incomeBTN?.addEventListener("click", () => {
  incomeForm.classList.remove("hidden");
});

expencesBTN?.addEventListener("click", () => {
  expencesForm.classList.remove("hidden");
});

/* ===============================
   CLOSE MODALS (CLICK BACKDROP)
================================ */
document.addEventListener("click", (e) => {
  if (e.target.classList.contains("bgblur")) {
    incomeForm?.classList.add("hidden");
    expencesForm?.classList.add("hidden");
    document.querySelectorAll(".dynamic-modal").forEach(m => m.remove());
  }
});

/* ===============================
   STOP PROPAGATION INSIDE MODALS
================================ */
document.addEventListener("click", (e) => {
  if (e.target.closest(".cont")) {
    e.stopPropagation();
  }
});

/* ===============================
   EDIT INCOME
================================ */
document.querySelectorAll(".edit_btn_inc").forEach(btn => {
  btn.addEventListener("click", (e) => {
    const row = e.target.closest(".element");

    const modal = document.createElement("div");
    modal.className = "dynamic-modal";

    modal.innerHTML = `
      <div class="bgblur fixed inset-0 backdrop-blur-sm bg-black/40 flex items-center justify-center z-[999]">
        <div class="cont bg-white w-96 rounded-xl shadow-lg p-6">
          <h2 class="text-xl font-bold text-center mb-4">Edit Income</h2>

          <form method="post">
            <input type="hidden" name="id" value="${row.dataset.id}">

            <div class="mb-3">
              <label class="block text-sm font-semibold">Amount</label>
              <input type="text" name="edit-Incomes"
                class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
              <label class="block text-sm font-semibold">Description</label>
              <input type="text" name="edit-descreption"
                class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
              <label class="block text-sm font-semibold">Date</label>
              <input type="date" name="edit-date"
                class="w-full border rounded px-3 py-2">
            </div>

            <button class="w-full py-2 rounded bg-gradient-to-r from-green-500 to-blue-500 text-white font-bold">
              Save Changes
            </button>
          </form>
        </div>
      </div>
    `;

    contInc.appendChild(modal);
  });
});

/* ===============================
   EDIT EXPENSE
================================ */
document.querySelectorAll(".edit_btn_exp").forEach(btn => {
  btn.addEventListener("click", (e) => {
    const row = e.target.closest(".Eelement");

    const modal = document.createElement("div");
    modal.className = "dynamic-modal";

    modal.innerHTML = `
      <div class="bgblur fixed inset-0 backdrop-blur-sm bg-black/40 flex items-center justify-center z-[999]">
        <div class="cont bg-white w-96 rounded-xl shadow-lg p-6">
          <h2 class="text-xl font-bold text-center mb-4">Edit Expense</h2>

          <form method="post">
            <input type="hidden" name="id" value="${row.dataset.id}">

            <div class="mb-3">
              <label class="block text-sm font-semibold">Amount</label>
              <input type="text" name="edit-Expenses"
                class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
              <label class="block text-sm font-semibold">Description</label>
              <input type="text" name="edit-Edescreption"
                class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
              <label class="block text-sm font-semibold">Date</label>
              <input type="date" name="edit-Edate"
                class="w-full border rounded px-3 py-2">
            </div>

            <button class="w-full py-2 rounded bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold">
              Save Changes
            </button>
          </form>
        </div>
      </div>
    `;

    contExp.appendChild(modal);
  });
});

/* ===============================
   DELETE INCOME
================================ */
document.querySelectorAll(".delete_btn_inc").forEach(btn => {
  btn.addEventListener("click", (e) => {
    const row = e.target.closest(".element");

    const form = document.createElement("form");
    form.method = "post";
    form.innerHTML = `<input type="hidden" name="Did" value="${row.dataset.id}">`;

    document.body.appendChild(form);
    form.submit();
  });
});

/* ===============================
   DELETE EXPENSE
================================ */
document.querySelectorAll(".delete_btn_exp").forEach(btn => {
  btn.addEventListener("click", (e) => {
    const row = e.target.closest(".Eelement");

    const form = document.createElement("form");
    form.method = "post";
    form.innerHTML = `<input type="hidden" name="EDid" value="${row.dataset.id}">`;

    document.body.appendChild(form);
    form.submit();
  });
});
