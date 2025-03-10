<?php
// Get content from layout
ob_start();
include __DIR__ . '/Layouts/Home.php';
$content = ob_get_clean();

// Include in global layout
include __DIR__ . '/Layouts/GlobalLayout.php';

?>
<div class="welcome">
    <h2><?= htmlspecialchars($title) ?></h2>
    <p><?= htmlspecialchars($message) ?></p>
    
    <div class="info">
        <h3>Getting Started</h3>
        <ul>
            <li>Define routes in <code>app/Routes/web.php</code></li>
            <li>Create controllers in <code>app/Controllers/</code></li>
            <li>Create views in <code>app/Views/</code></li>
            <li>Configure your application in <code>.env</code> file</li>
        </ul>
    </div>
</div>

<style>
    .welcome {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 20px;
        margin: 20px 0;
    }
    .info {
        background-color: #f9f9f9;
        padding: 15px;
        border-left: 4px solid #4CAF50;
        margin-top: 20px;
    }
    code {
        background-color: #eee;
        padding: 2px 5px;
        border-radius: 3px;
        font-family: monospace;
    }
</style>