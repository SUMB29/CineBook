<?php
    $apikey = "rzp_test_R8d4XFJwLWyHd2";
?>
<?php
session_start();
include "connect.php";
try {
    $pdo = new PDO($attr, $user, $pass, $opts);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}



$claim_id = (int) ($_GET['payment'] ?? 0);

if ($claim_id <= 0) {
    die("Invalid Booking ID");
}

$query = "
    SELECT 
        b.bookingid AS bid,
        b.status,
        b.bookingdate AS bdate,
        m.title AS movietitle,
        b.seats AS bseat,
        t.name AS tname,
        t.price AS tprice,
        t.Location AS tlocation
    FROM booking b
    JOIN movies m ON b.movieid = m.movieid
    JOIN theatre t ON b.theatreid = t.theatreid
    WHERE b.bookingid = :bookingid
";

$stmt1 = $pdo->prepare($query);
$stmt1->execute([':bookingid' => $claim_id]);
$booking = $stmt1->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("No booking found for booking ID: " . htmlspecialchars($claim_id));
}

$cntSeat = !empty($booking['bseat'])
    ? count(array_filter(explode(',', $booking['bseat'])))
    : 0;

$bill = $booking['tprice'] * $cntSeat;

?>


<form method="POST">
<script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?= htmlspecialchars($apikey) ?>"
    data-amount="<?= intval($bill * 100) ?>"
    data-currency="INR"
    data-id="<?= 'OID' . rand(1000,9999) ?>"
    data-buttontext="Pay with Razorpay"
    data-name="CineBook"
    data-description="Book Your Movie Ticket Online"
    data-prefill.name="<?= htmlspecialchars($_SESSION['name'] ?? '') ?>"
    data-theme.color="#e53935"
    data-methods='{"upi": true, "card": true, "netbanking": true, "wallet": true}'
    data-upi.flow="collect"
></script>



<input type="hidden" name="bookingid" value="<?= $claim_id ?>">
</form>


