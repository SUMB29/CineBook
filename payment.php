<?php
session_start();
include('connect.php'); 



try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
if (!isset($_GET['payment'])) {
    echo "<div class='text-center mt-10 text-red-600 text-lg'>Invalid Request</div>";
    exit;
}

$bookingid = intval($_GET['payment']);

$query = "
    SELECT 
        b.bookingid AS bid, b.status, b.bookingdate As bdate,
        m.title AS movietitle, b.seats AS bseat
        t.name As tname, t.price AS tprice, t.Location AS tlocation
    FROM booking b
    JOIN movies m ON b.movieid = m.movieid
    JOIN theatre t ON b.theatreid = t.theatreid
    WHERE b.bookingid = :bookingid
";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':bookingid', $bookingid, PDO::PARAM_INT);
$stmt->execute();
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

$cntSeat=count(explode(",",$booking['bseat']));

if (!$booking) {
    echo "<div class='text-center mt-10 text-red-600 text-lg'>Booking not found.</div>";
    exit;
}

// If already paid
if ($booking['status'] == 2) {
    echo "<div class='text-center mt-10 text-green-600 text-lg'>Payment already completed for this booking.</div>";
    exit;
}

// Handle payment confirmation
if (isset($_POST['confirm_payment'])) {
    $update = $pdo->prepare("UPDATE booking SET status = 2 WHERE bookingid = :bookingid");
    $update->bindParam(':bookingid', $bookingid, PDO::PARAM_INT);
    $update->execute();

    $pstmt=$pdo->prepare("INSERT INTO `payments`(`userid`, `bookingId`, `bookingdate`, `movie`, `theatrename`,
     `theatrelocation`, `price`) VALUES (:userid,:bid,:bdate,:movietitle,:tname,:tlocation,:tprice)");
    $pstmt->execute([
        ':userid'=>$_SESSION['userid'],
        ':bid'=>$booking['bid'],
        ':bdate'=>$booking['bdate'],
        ':movietitle'=>$booking['movietitle'],
        ':tname'=>$booking['tname'],
        ':tlocation'=>$booking['tlocation'],
        ':tprice'=>$booking['tprice'],
    ]);

    echo "<div class='text-center mt-10 text-green-600 text-lg'>Payment successful! Thank you.</div>";
    echo "<div class='text-center mt-4'>
            <a href='user_dashboard.php' class='bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded'>
                Go to Dashboard
            </a>
          </div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CineBook | Payment | Movie Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-cyan-950 flex items-center justify-center min-h-screen">

<div class="bg-black shadow-lg rounded-lg p-8 w-full max-w-md">

              <nav class="ml-40 justify-between items-center z-10">
        <span>
            <img src="assets/img/logo1.png" alt="Logo" width="53">
        </span>
    </nav>

    <h2 class="text-2xl font-bold text-center mb-4 text-white">Confirm Payment</h2>

    <div class="mb-6 text-white">
        <p><strong>Booking ID:</strong> <?= htmlspecialchars($booking['bid']) ?></p>
        <p><strong>Movie:</strong> <?= htmlspecialchars($booking['movietitle']) ?></p>
        <p><strong>Theatre:</strong> <?= htmlspecialchars($booking['tname']) ?></p>
        <p><strong>Amount:</strong> ₹<?= htmlspecialchars($booking['tprice'] * $cntSeat) ?></p>
    </div>

    <form method="POST">
        <button type="submit" name="confirm_payment"
            class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-semibold">
            <a href="razorpay.php?claim_id=' . $booking['bid'] . '">
            Pay ₹<?= htmlspecialchars($booking['tprice'] * $cntSeat) ?> Now</a>
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="user_dashboard.php" class="text-blue-500 hover:underline">Cancel</a>
    </div>
</div>

</body>
</html>
