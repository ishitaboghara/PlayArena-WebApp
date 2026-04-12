<?php
include 'db.php';

$result = $conn->query("SELECT * FROM grounds");

$images = [
    1 => [
        "https://images.unsplash.com/photo-1593341646782-e0b495cff86d",
        "https://images.unsplash.com/photo-1624880357913-a8539238245b"
    ],
    2 => [
        "https://images.unsplash.com/photo-1518604666860-9ed391f76460",
        "https://images.unsplash.com/photo-1508098682722-e99c43a406b2"
    ],
    3 => [
        "https://images.unsplash.com/photo-1626224583764-f87db24ac4ea",
        "https://images.unsplash.com/photo-1613918431703-aa5083c4f78f"
    ],
    4 => [
        "https://images.unsplash.com/photo-1504450758481-7338eba7524a"
    ],
    5 => [
        "https://images.unsplash.com/photo-1546519638-68e109498ffc"
    ],
    6 => [
        "https://images.unsplash.com/photo-1592656094267-764a45160876"
    ],
    7 => [
        "https://images.unsplash.com/photo-1609710228159-0fa9bd7c0827"
    ],
    8 => [
        "https://images.unsplash.com/photo-1504674900247-0877df9cc836"
    ]
];

while($row = $result->fetch_assoc()) {

    $sport_id = $row['sport_id'];
    $list = $images[$sport_id];

    $img = $list[$row['id'] % count($list)];

    $conn->query("UPDATE grounds SET image_url='$img' WHERE id=".$row['id']);
}

echo "Images Updated!";