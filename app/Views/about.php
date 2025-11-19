<?php ob_start(); ?>

<h1><?= htmlspecialchars($title ?? 'About') ?></h1>

<div style="margin: 30px 0;">
    <h2 style="color: #667eea; margin-bottom: 15px;">About This Framework</h2>
    <p style="color: #666; line-height: 1.8; margin-bottom: 20px;">
        This is a beginner-friendly PHP MVC framework built for learning and understanding.
        Unlike complex frameworks with "magic" and heavy abstractions, this framework keeps things
        simple and clear - perfect for students learning web development and MVC architecture.
    </p>

    <h2 style="color: #667eea; margin: 30px 0 15px;">Philosophy</h2>
    <p style="color: #666; line-height: 1.8; margin-bottom: 20px;">
        <strong>No Magic, Just Code</strong> - Every SQL query is visible. Every step is clear.
        You write plain SQL in your models, so you understand exactly what's happening with your data.
        This helps you learn how databases really work, not just how to use an ORM.
    </p>

    <h2 style="color: #667eea; margin: 30px 0 15px;">Core Components</h2>

    <h3 style="color: #764ba2; margin: 20px 0 10px;">1. Router</h3>
    <p style="color: #666; line-height: 1.8; margin-bottom: 15px;">
        Handles all HTTP methods (GET, POST, PUT, DELETE), supports route parameters like <code>/users/{id}</code>,
        route groups with prefixes, and middleware. Routes can point to controllers or closures.
    </p>

    <h3 style="color: #764ba2; margin: 20px 0 10px;">2. Controllers</h3>
    <p style="color: #666; line-height: 1.8; margin-bottom: 15px;">
        Controllers coordinate between requests, models, and responses. They load models with
        <code>$this->loadModel()</code>, get data, and return views or JSON. Clear, step-by-step logic.
    </p>

    <h3 style="color: #764ba2; margin: 20px 0 10px;">3. Models (No ORM!)</h3>
    <p style="color: #666; line-height: 1.8; margin-bottom: 15px;">
        Models are simple classes with methods that contain plain SQL queries. No automatic magic,
        no hidden behavior. You write <code>SELECT * FROM users</code> and see exactly what happens.
    </p>
    <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; margin: 15px 0; overflow-x: auto;"><code>public function getAllUsers() {
    $sql = "SELECT * FROM users";
    return $this->db->query($sql);
}</code></pre>

    <h3 style="color: #764ba2; margin: 20px 0 10px;">4. Database Layer</h3>
    <p style="color: #666; line-height: 1.8; margin-bottom: 15px;">
        Simple PDO wrapper with two methods:
        <br>&bull; <code>query($sql, $params)</code> - for SELECT queries (returns array)
        <br>&bull; <code>execute($sql, $params)</code> - for INSERT/UPDATE/DELETE (returns affected rows)
        <br>All queries use prepared statements for security.
    </p>

    <h3 style="color: #764ba2; margin: 20px 0 10px;">5. Views</h3>
    <p style="color: #666; line-height: 1.8; margin-bottom: 15px;">
        Plain PHP templates with data passed from controllers. Use <code>htmlspecialchars()</code>
        to prevent XSS. Simple layout system with output buffering.
    </p>

    <h3 style="color: #764ba2; margin: 20px 0 10px;">6. Middleware</h3>
    <p style="color: #666; line-height: 1.8; margin-bottom: 15px;">
        Add authentication, logging, CORS, or custom logic before/after requests.
        Easy to create and apply to routes.
    </p>

    <h2 style="color: #667eea; margin: 30px 0 15px;">Perfect for Learning</h2>
    <ul style="color: #666; line-height: 2; margin-left: 20px;">
        <li>Understand MVC architecture without framework magic</li>
        <li>Learn SQL by writing actual queries</li>
        <li>See the complete request flow clearly</li>
        <li>Easy to trace errors and debug</li>
        <li>Build on a solid foundation</li>
    </ul>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>
