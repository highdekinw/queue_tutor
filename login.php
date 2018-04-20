<?php require_once 'element/header.php'; ?>
    <form action="backend/login.php" method="post">
        <div class="row">
        <div class="col s12 m12">
            <h6 class="input-label">ชื่อผู้ใช้</h6>
            <input type="text" name="id" required>
        </div>
        <div class="col s12 m12">
            <h6 class="input-label">รหัสผ่าน</h6>
            <input type="password" name="password" required>
        </div>
        <div class="col s12 m6">
            <button type="submit" class="btn btn-default btn-block blue">เข้าสู่ระบบ</button>
        </div>
        <div class="col s12 m6">
            <a class="btn btn-default btn-block grey" href="signup.php">สมัครสมาชิก</a>
        </div>
        </div>
    </form>
<?php require_once 'element/footer.php'; ?>