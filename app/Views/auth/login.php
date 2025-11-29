<div class="auth-card">
    <div class="text-center mb-4">
        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-subtitle">Please sign in to your account</p>
        <?php if (isset($errors['login'])): ?>
            <div class="alert alert-danger">
                <?php echo $errors['login']; ?>
            </div>
        <?php endif; ?>
    </div>

    <form action="/login" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label text-sm fw-medium text-gray-700">Username</label>
            <input type="username" class="form-control" id="username" name="username" placeholder="admin" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-sm fw-medium text-gray-700">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
        </div>
        <!-- TODO: Contemplate if forgot password is needed -->
        <!-- 
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="#" class="text-sm text-decoration-none" style="color: #4f46e5;">Forgot password?</a>
        </div> -->

        <button type="submit" class="btn btn-primary">
            Sign in
        </button>
    </form>
</div>