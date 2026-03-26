<?php
// Truy vấn danh sách đánh giá
$query = "SELECT t.HOTEN, r.rating, r.comment, r.created_at 
          FROM rating r 
          JOIN taikhoan t ON r.idTK = t.idTK 
          WHERE r.idSP = ? 
          ORDER BY r.created_at DESC";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $sanpham['idSP']);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../../css/reviews.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div id="reviews" class="reviews">
    <?php if ($result->num_rows > 0): ?>
        <ul class="review-list">
            <?php 
            $count = 0;
            while ($row = $result->fetch_assoc()): 
                $count++;
                $hoten = htmlspecialchars($row['HOTEN']);
                $firstLetter = mb_substr(trim($hoten), 0, 1, 'UTF-8');
            ?>
                <li class="review-item <?= $count > 3 ? 'hidden-review' : '' ?>">
                    <div class="review-header">
                        <div class="info-reviewer">
                            <div class="avatar"><?= $firstLetter ?></div>
                            <div class="name-and-time">
                                <strong><?= $hoten ?></strong>
                                <span class="review-date">
                                    <i class="fa fa-clock-o"></i>
                                    <?= date("d/m/Y H:i", strtotime($row['created_at'])) ?>
                                </span>
                            </div>
                        </div>

                        <div class="review-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star <?= $i <= $row['rating'] ? 'filled' : '' ?>"><i class="fa fa-star"></i></span>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <p class="review-comment"><?= nl2br(htmlspecialchars($row['comment'])) ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
        
        <?php if ($count > 3): ?>
            <button id="show-more-reviews">
                Xem thêm đánh giá <i class="fa fa-chevron-down"></i>
            </button>
        <?php endif; ?>
    <?php else: ?>
        <p>Chưa có đánh giá nào.</p>
    <?php endif; ?>
</div>

<script src="../../js/showproduct/review.js"></script>