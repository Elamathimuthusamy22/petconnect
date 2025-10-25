<?php
session_start();
include 'db_connect.php';

// --- Normalize session user ID ---
$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? ($_SESSION['user'] ?? 'User');

if (!$user_id) {
    header('Location: index.php?page=login');
    exit();
}

// --- Initialize arrays ---
$appointments = $feedbacks = $pets = $lostReports = $petshopItems = [];

// --- Fetch Appointments ---
try {
    $appointments = iterator_to_array($db->appointments->find(
        ['user_id' => $user_id],
        ['sort' => ['created_at' => -1]]
    ));
} catch (Exception $e) {}

// --- Fetch Feedbacks ---
try {
    $feedbacks = iterator_to_array($db->feedback->find(
        ['user_id' => $user_id],
        ['sort' => ['created_at' => -1]]
    ));
} catch (Exception $e) {}

// --- Fetch Pets added by user ---
try {
    $pets = iterator_to_array($db->pets->find(
        ['created_by' => $user_id],
        ['sort' => ['created_at' => -1]]
    ));
} catch (Exception $e) {}

// --- Fetch Lost & Found Reports ---
try {
    $lostReports = iterator_to_array($db->lost_found->find(
        ['user_id' => $user_id],
        ['sort' => ['created_at' => -1]]
    ));
} catch (Exception $e) {}

// --- Fetch Petshop Cart items (MongoDB user-specific) ---
try {
    $cartDoc = $db->cart->findOne(['user_id' => $user_id]);
    if ($cartDoc && isset($cartDoc['items'])) {
        $itemIds = array_map(fn($i) => new MongoDB\BSON\ObjectId($i['item_id']), $cartDoc['items']);
        if (!empty($itemIds)) {
            $petshopItems = iterator_to_array($db->petshop->find(['_id' => ['$in' => $itemIds]]));
        }
    }
} catch (Exception $e) {}

// --- Date Formatter ---
function fmtDate($d) {
    if ($d instanceof MongoDB\BSON\UTCDateTime) return $d->toDateTime()->format('M d, Y H:i');
    if (is_string($d)) return htmlspecialchars($d);
    return 'N/A';
}
?>

<div style="max-width:1100px;margin:30px auto;padding:20px;">
    <h1 style="font-size:32px;color:#2d3436;margin-bottom:6px;">Hello, <?php echo htmlspecialchars($user_name); ?></h1>
    <p style="color:#636e72;margin-bottom:20px;">This dashboard summarizes your recent actions on PetConnect.</p>

    <!-- Summary Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-bottom:24px;">
        <?php
        $summary = [
            ['title'=>'Appointments','count'=>count($appointments),'desc'=>'Upcoming and past appointments you created'],
            ['title'=>'Feedbacks','count'=>count($feedbacks),'desc'=>'Feedback messages you submitted'],
            ['title'=>'Pets Added','count'=>count($pets),'desc'=>'Pets recorded with your account'],
            ['title'=>'Cart Items','count'=>count($petshopItems),'desc'=>'Items currently in your pet shop cart']
        ];
        foreach($summary as $s):
        ?>
        <div style="background:#fff;padding:16px;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,0.06);">
            <div style="font-size:20px;font-weight:700;color:#2d3436;"><?php echo $s['title']; ?></div>
            <div style="font-size:28px;color:#ff6348;margin-top:8px;"><?php echo $s['count']; ?></div>
            <div style="color:#8b9498;margin-top:8px;font-size:13px;"><?php echo $s['desc']; ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Recent Activities -->
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
        <!-- Appointments -->
        <div style="background:#fff;padding:18px;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,0.06);">
            <h2 style="margin-top:0;color:#2d3436;">Recent Appointments</h2>
            <?php if(empty($appointments)): ?>
                <p style="color:#636e72;">No appointments yet. <a href="index.php?page=appointments">Book now</a>.</p>
            <?php else: ?>
            <table style="width:100%;border-collapse:collapse;">
                <thead><tr style="text-align:left;border-bottom:1px solid #eee;">
                    <th style="padding:10px">Date</th><th style="padding:10px">Pet</th><th style="padding:10px">Service</th><th style="padding:10px">Status</th>
                </tr></thead>
                <tbody>
                <?php foreach($appointments as $a): ?>
                <tr style="border-bottom:1px solid #fafafa;">
                    <td style="padding:10px"><?php echo fmtDate($a['created_at'] ?? $a['date'] ?? null); ?></td>
                    <td style="padding:10px"><?php echo htmlspecialchars($a['pet_name'] ?? $a['name'] ?? '—'); ?></td>
                    <td style="padding:10px"><?php echo htmlspecialchars($a['service'] ?? '-'); ?></td>
                    <td style="padding:10px"><?php echo htmlspecialchars($a['status'] ?? 'pending'); ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- Petshop Cart -->
        <div style="background:#fff;padding:18px;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,0.06);">
            <h2 style="margin-top:0;color:#2d3436;">Your Cart</h2>
            <?php if(empty($petshopItems)): ?>
                <p style="color:#636e72;">Your cart is empty. <a href="index.php?page=pet_shop">Browse items</a>.</p>
            <?php else: ?>
                <ul style="list-style:none;padding:0;margin:0;">
                <?php foreach($petshopItems as $it): ?>
                    <li style="padding:10px 0;border-bottom:1px solid #f4f4f4;">
                        <div style="font-weight:700;color:#2d3436"><?php echo htmlspecialchars($it['name']); ?></div>
                        <div style="color:#8b9498;font-size:13px;">Price: $<?php echo htmlspecialchars($it['price']); ?> · Qty: <?php echo htmlspecialchars($it['quantity']); ?></div>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
