<?php
session_start();


require 'Database.php';
require_once 'Expences.php';
require_once 'Income.php';


    
$db = new Database();
$sql_con = $db->getConnection();
$exp_tracker = new Expences($sql_con);
$inc_tracker = new Incomes($sql_con);





$userEmail = $_SESSION['user'] ?? 'Guest';

if (isset($_SESSION['user'])) {
    $email = $_SESSION['user'];
    $emailHash = md5(strtolower(trim($email)));
    $gravatarUrl = "https://www.gravatar.com/avatar/$emailHash?d=identicon&s=80";
} else {
    // default avatar when not logged in
    $gravatarUrl = "https://www.gravatar.com/avatar/?d=mp&s=80";
}


if (!$_SESSION['user']){
  header("Location:index.php");
}





if (isset($_POST['Expences']) && isset($_POST['descreption_exp']))  {
  $amount = (int) trim($_POST['Expences']);
  $desc = trim($_POST['descreption_exp']);
  $date = $_POST['Date_exp1'];
  $category = trim($_POST['category_exp']);

    if ($amount > 0) {
      $exp_tracker->addExpence($amount, $category, $desc,  $date, $_SESSION['user']);
            header("Location: Home.php");
      exit;
    }

}

      if (isset($_POST['edit-Expenses'])){
        $exp_tracker->updateExpence(
          intval($_POST['id']),
          (int) trim($_POST['edit-Expenses']),
          (string) trim($_POST['edit-Edescreption']),
          $_POST['edit-Edate']  ?? null
        );
      }
            
      if (isset($_POST['EDid'])){
        $exp_tracker->deleteExpence((int) $_POST['EDid'], $email);
              header("Location: Home.php");
      exit;
      }


if (isset($_POST['Incomes']) && isset($_POST['description_inc']))  {
    $amount_inc = (int) trim($_POST['Incomes']);
    $desc_inc = trim($_POST['description_inc']);
    $date_inc = !empty($_POST['date_inc']) ? $_POST['date_inc'] : null;
    $category = trim($_POST['category_inc']);

    if ($amount_inc > 0) {
      $inc_tracker->addIncome($amount_inc, $category, $desc_inc, $date_inc, $email);
      header("Location: Home.php");
      exit;
    }

}

      if (isset($_POST['edit-Incomes'])){
        $inc_tracker->updateIncome(
          intval($_POST['id']),
          (int) trim($_POST['edit-Incomes']),
          (string) trim($_POST['edit-descreption']),
          $_POST['edit-date']  ?? null
        );
      }
            
      if (isset($_POST['Did'])){
        $inc_tracker->deleteIncome((int) $_POST['Did']);
              header("Location: Home.php");
      exit;
      }

      $result_exp = $exp_tracker->ExpensesList($email);
      $total_expenses = $exp_tracker->ExpensesSum($email);


      $result_inc = $inc_tracker->IncomesList($email);
      $total_income = $inc_tracker->IncomesSum($email);

      if (isset($_GET['income-category']) && $_GET['income-category'] !== 'All'){
        $category = $_GET['income-category'];
        $result_inc = $inc_tracker->GetIncomesByCategory($category, $email);
      }

      if (isset($_GET['expense-category']) && $_GET['expense-category'] !== 'All'){
        $category_exp = $_GET['expense-category'];
        $result_exp = $exp_tracker->GetExpensesByCategory($category_exp, $email);
      }


?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Smart Wallet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <?php
if (isset($_GET['error']) && $_GET['error'] === 'email_exists') {
    echo "<script>alert('Email already registered');</script>";
    
}

if (isset($_GET['success']) && $_GET['success'] === 'signup') {
    echo "<script>alert('Signup successful! Please login.');</script>";
}




?>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#2563eb',
            danger: '#dc2626',
            success: '#16a34a'
          }
        }
      }
    }
  </script>
</head>

<style>
  /* From Uiverse.io by fthisilak */ 
.incomes-btn, .expenses-btn {
  position: relative;
  padding: 12px 24px;
  font-size: 16px;
  background: #1a1a1a;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
}

.incomes-btn, .expenses-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
}

.icon-container {
  position: relative;
  width: 24px;
  height: 24px;
}

.icon {
  position: absolute;
  top: 0;
  left: 0;
  width: 24px;
  height: 24px;
  color: #22c55e;
  opacity: 0;
  visibility: hidden;
}

.default-icon {
  opacity: 1;
  visibility: visible;
}

/* Hover animations */
.incomes-btn, .expenses:hover .icon {
  animation: none;
}

.incomes-btn, .expenses:hover .wallet-icon {
  opacity: 0;
  visibility: hidden;
}

.incomes-btn, .expenses-btn:hover .card-icon {
  animation: iconRotate 2.5s infinite;
  animation-delay: 0s;
}

.incomes-btn, .expenses-btn:hover .payment-icon {
  animation: iconRotate 2.5s infinite;
  animation-delay: 0.5s;
}

.incomes-btn, .expenses-btn:hover .dollar-icon {
  animation: iconRotate 2.5s infinite;
  animation-delay: 1s;
}

.incomes-btn, .expenses-btn:hover .check-icon {
  animation: iconRotate 2.5s infinite;
  animation-delay: 1.5s;
}

/* Active state - show only checkmark */
.imcomes-btn:active .icon, .expenses-btn:active .icon {
  animation: none;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.incomes-btn,.expenses-btn:active .check-icon {
  animation: checkmarkAppear 0.6s ease forwards;
  visibility: visible;
}

.btn-text {
  font-weight: 600;
  font-family:
    system-ui,
    -apple-system,
    sans-serif;
}

@keyframes iconRotate {
  0% {
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px) scale(0.5);
  }
  5% {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
  }
  15% {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
  }
  20% {
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px) scale(0.5);
  }
  100% {
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px) scale(0.5);
  }
}

@keyframes checkmarkAppear {
  0% {
    opacity: 0;
    transform: scale(0.5) rotate(-45deg);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.2) rotate(0deg);
  }
  100% {
    opacity: 1;
    transform: scale(1) rotate(0deg);
  }
}

</style>

<body class="bg-gray-100 text-gray-800 font-sans">

<!-- HEADER -->
<header class="bg-gradient-to-r from-green-600 to-blue-600 shadow">
  <div class="absolute right-20 top-2 flex gap-1 items-center">
  <p class=" text-white ml-auto mr-4 font-semibold">
        <?php echo htmlspecialchars($userEmail); ?>
    </p>
    <img src="<?= $gravatarUrl ?>" class=" rounded-full w-10 h-10" alt="User avatar">
</div>

    <a href="user_login.php"><button id="user" class="absolute w-5 h-12  right-5 top-1"><svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M144 128a80 80 0 1 1 160 0 80 80 0 1 1 -160 0zm208 0a128 128 0 1 0 -256 0 128 128 0 1 0 256 0zM48 480c0-70.7 57.3-128 128-128l96 0c70.7 0 128 57.3 128 128l0 8c0 13.3 10.7 24 24 24s24-10.7 24-24l0-8c0-97.2-78.8-176-176-176l-96 0C78.8 304 0 382.8 0 480l0 8c0 13.3 10.7 24 24 24s24-10.7 24-24l0-8z"/></svg>
  </button></a>
      <button id="logout-btn" class="absolute right-[22rem] top-3 text-danger pt-1 pb-1 pl-2 pr-2 bg-black rounded font-extrabold">
  Logout
</button>
  <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
    <h1 class="text-white text-2xl font-bold">Smart Wallet</h1>
    <img src="2dde7ddf-9a38-400b-94d2-c90c14b33677.jpg" class="absolute left-[2rem] w-10 h-10 rounded-full">
  </div>


    <!-- Background Overlay -->
<div id="logout-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
  <div id="logout-bg" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

  <div class="cont relative w-full max-w-sm rounded-2xl bg-white shadow-xl p-6 animate-fadeIn">
    <h2 class="text-lg font-semibold text-gray-800 text-center mb-6">
      Do you want to logout from your account?
    </h2>
    <div class="flex gap-4">
      <button id="cancel-logout" class="w-1/2 rounded-xl border border-gray-300 py-2 text-gray-700 font-medium
               transition-all duration-300 hover:bg-gray-100 hover:scale-[1.02]">
        Cancel
      </button>
      <form action="user_login.php" method="post" class="w-1/2">
        <button type="submit" name = "logout"
          class="w-full rounded-xl bg-red-500 py-2 text-white font-semibold
                 transition-all duration-300 hover:bg-red-600 hover:scale-[1.02]">
          Logout
        </button>
      </form>
    </div>
  </div>
</div>
</header>

<!-- MODAL CONTAINERS (DO NOT TOUCH IDS) -->
<div id="cont_inc"></div>
<div id="cont_exp"></div>

<!-- ADD INCOME MODAL -->
<div id="incomes" class="hidden">
  <div class="bgblur fixed inset-0 bg-black/40 flex items-center justify-center z-[999]">
    <div class="cont bg-white w-96 rounded-xl shadow-xl">
      <div class="p-4 border-b text-center font-bold text-lg">Add Income</div>

      <form method="post" class="p-4 space-y-4">
        <input name="Incomes" class="w-full border rounded px-3 py-2" placeholder="Amount">
        <input name="category_inc" class="w-full border rounded px-3 py-2" placeholder="Category (e.g. Salary)" list = "categories">
        <datalist id="categories">
          <option value="Food">
          <option value="Transport">
          <option value="Groceries">
          <option value="Gym">
          <option value="Trips">
          <option value="other">
        </datalist>
        <input name="description_inc" class="w-full border rounded px-3 py-2" placeholder="Description">
        <input type="date" name="date_inc" class="w-full border rounded px-3 py-2">

        <button class="w-full py-2 bg-primary text-white rounded font-semibold">
          Save Income
        </button>
      </form>
    </div>
  </div>
</div>

<!-- ADD EXPENSE MODAL -->
<div id="expences" class="hidden">
  <div class="bgblur fixed inset-0 bg-black/40 flex items-center justify-center z-[999]">
    <div class="cont bg-white w-96 rounded-xl shadow-xl">
      <div class="p-4 border-b text-center font-bold text-lg">Add Expense</div>

      <form method="post" class="p-4 space-y-4">
        <input name="Expences" class="w-full border rounded px-3 py-2" placeholder="Amount">
                <input name="category_exp" class="w-full border rounded px-3 py-2" placeholder="Category (e.g. Salary)" list = "categories">
        <datalist id="categories">
          <option value="Food_exp">
          <option value="Transport_exp">
          <option value="Groceries_exp">
          <option value="Gym_exp">
          <option value="Trips_exp">
          <option value="other_exp">
        </datalist>
        <input name="descreption_exp" class="w-full border rounded px-3 py-2" placeholder="Description">
        <input type="date" name="Date_exp1" class="w-full border rounded px-3 py-2">

        <button class="w-full py-2 bg-primary text-white rounded font-semibold">
          Save Expense
        </button>
      </form>
    </div>
  </div>
</div>

<!-- ACTION BUTTONS -->
<!-- From Uiverse.io by fthisilak --> 
<div class="flex justify-center gap-12 my-12">
  
  <!-- INCOME BUTTON -->
  <button class="incomes-btn relative mt-12 mb-12">
    <span class="btn-text">Add Income</span>
    <div class="icon-container">
      <svg viewBox="0 0 24 24" class="icon card-icon">
        <path
          d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18C2,19.11 2.89,20 4,20H20C21.11,20 22,19.11 22,18V6C22,4.89 21.11,4 20,4Z"
          fill="currentColor"
        />
      </svg>

      <svg viewBox="0 0 24 24" class="icon dollar-icon">
        <path
          d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"
          fill="currentColor"
        />
      </svg>

      <svg viewBox="0 0 24 24" class="icon wallet-icon default-icon">
        <path
          d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5A2,2 0 0,1 5,3H19A2,2 0 0,1 21,5V6H12C10.89,6 10,6.9 10,8V16A2,2 0 0,0 12,18M12,16H22V8H12"
          fill="currentColor"
        />
      </svg>

      <svg viewBox="0 0 24 24" class="icon check-icon">
        <path
          d="M9,16.17L4.83,12L3.41,13.41L9,19L21,7L19.59,5.59Z"
          fill="currentColor"
        />
      </svg>
    </div>
  </button>

  <!-- EXPENSE BUTTON -->
  <button class="expenses-btn relative mt-12 mb-12">
    <span class="btn-text">Add Expense</span>
    <div class="icon-container">
      <svg viewBox="0 0 24 24" class="icon payment-icon">
        <path
          d="M2,17H22V21H2V17M6.25,7H9V6H6V3H18V6H15V7H17.75L19,17H5L6.25,7Z"
          fill="currentColor"
        />
      </svg>

      <svg viewBox="0 0 24 24" class="icon dollar-icon">
        <path
          d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85"
          fill="currentColor"
        />
      </svg>

      <svg viewBox="0 0 24 24" class="icon wallet-icon default-icon">
        <path
          d="M21,18V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5"
          fill="currentColor"
        />
      </svg>

      <svg viewBox="0 0 24 24" class="icon check-icon">
        <path
          d="M9,16.17L4.83,12L3.41,13.41L9,19L21,7Z"
          fill="currentColor"
        />
      </svg>
    </div>
  </button>

</div>




<!-- DASHBOARD CARDS -->
<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

  <div class="bg-white rounded-xl shadow p-5">
    <p class="text-sm text-gray-500">Total Expenses</p>
    <h2 class="text-2xl font-bold text-danger">$<?= $total_expenses ?? 0 ?></h2>
  </div>

  <div class="bg-white rounded-xl shadow p-5">
    <p class="text-sm text-gray-500">Total Income</p>
    <h2 class="text-2xl font-bold text-success">$<?= $total_income ?></h2>
  </div>

  <div class="bg-white rounded-xl shadow p-5">
    <p class="text-sm text-gray-500">Net Profit</p>
    <h2 class="text-2xl font-bold">
      $<?= $total_income - $total_expenses ?>
    </h2>
  </div>

</div>

<!-- CHART -->
<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow mb-10">
  <canvas id="myChart"></canvas>
</div>

<!-- TABLES -->
<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-6">

  
  
  
  <!-- INCOME TABLE -->
  <div class="bg-gray-800 rounded-xl overflow-hidden">
  <!-- FILTER FORM FOR INCOME TABLE -->
  <div class="max-w-7xl mx-auto px-6 mb-4">
    <form id="filter-incomes-form" method="GET" class="flex gap-4">
      <select name="income-category" class="px-4 py-2 border rounded bg-white">
        <option value="All">Select Income Category</option>
        <option value="Food">Food</option>
        <option value="Transport">Transport</option>
        <option value="Groceries">Groceries</option>
        <option value="Gym">Gym</option>
        <option value="Trips">Trips</option>
        <option value="other">Other</option>
      </select>
      <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Filter</button>
    </form>
  </div>
<table class="w-full text-sm text-white">
<tbody>
<?php foreach($result_inc as $row_inc): ?>
<tr class="element border-b border-gray-700" data-id="<?= $row_inc['inc_id'] ?>">
  <td class="p-3">$<?= $row_inc['Incomes'] ?></td>
  <td class="p-3"><?= $row_inc['Date'] ?></td>
  <td class="p-3"><?= $row_inc['description'] ?></td>
  <td class="p-3"><?= $row_inc['Category'] ?></td>
  <td class="p-3 text-cyan edit_btn_inc cursor-pointer">Edit</td>
  <td class="p-3 text-red-500 delete_btn_inc cursor-pointer">Delete</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>



<!-- EXPENSE TABLE -->
<div class="bg-gray-800 rounded-xl overflow-hidden">
  <!-- FILTER FORM FOR EXPENSE TABLE -->
  <div class="max-w-7xl mx-auto px-6 mb-4">
    <form id="filter-expenses-form" method="GET" class="flex gap-4">
      <select name="expense-category" class="px-4 py-2 border rounded bg-white">
        <option value="All">Select Expense Category</option>
        <option value="Food">Food</option>
        <option value="Transport">Transport</option>
        <option value="Groceries">Groceries</option>
        <option value="Gym">Gym</option>
        <option value="Trips">Trips</option>
        <option value="other">Other</option>
      </select>
      <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Filter</button>
    </form>
  </div>
<table class="w-full text-sm text-white">
<tbody>
<?php
 foreach($result_exp as $row_exp): ?>
<tr class="Eelement border-b border-gray-700" data-id="<?= $row_exp['exp_id'] ?>">
  <td class="p-3">$<?= $row_exp['Expences'] ?></td>
  <td class="p-3"><?= $row_exp['Date'] ?></td>
  <td class="p-3"><?= $row_exp['description'] ?></td>
  <td class="p-3"><?= $row_exp['category'] ?></td>
  <td class="p-3 text-cyan edit_btn_exp cursor-pointer">Edit</td>
  <td class="p-3 text-red-500 delete_btn_exp cursor-pointer">Delete</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

</div>
<script>
  function animateButton(btn) {
    btn.classList.remove("ring-4", "ring-white/50");
    void btn.offsetWidth; // reset animation
    btn.classList.add("ring-4", "ring-white/50");

    setTimeout(() => {
      btn.classList.remove("ring-4", "ring-white/50");
    }, 300);
  }

  document.getElementById("incomes-btn").addEventListener("click", function () {
    animateButton(this);
  });

  document.getElementById("expenses-btn").addEventListener("click", function () {
    animateButton(this);
  });
</script>


<script src="script.js"></script>

<script>
const ctx = document.getElementById('myChart');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Income', 'Expenses', 'Net'],
    datasets: [{
      data: [<?= $total_income ?>, <?= $total_expenses ?>, <?= $total_income - $total_expenses ?>],
      backgroundColor: ['#16a34a','#dc2626','#2563eb']
    }]
  },
  options: { responsive: true }
});
</script>

</body>
</html>
