<?php namespace App\Controllers;

use App\Models\MembersModel;
use App\Models\ProfilesModel;
use App\Models\PermissionModel;

/**
 * Development Login Controller
 * IMPORTANT: ONLY USE IN DEVELOPMENT MODE - REMOVE IN PRODUCTION!
 * 
 * This controller bypasses OAuth for local development
 * Access: http://localhost:8888/cbi/DevLogin
 */
class DevLogin extends BaseController
{
    public function debug()
    {
        // Check if we're in development mode
        if (ENVIRONMENT !== 'development') {
            die('This feature is only available in development mode.');
        }
        
        echo "<h1>Debug Information</h1>";
        echo "<h2>Environment</h2>";
        echo "Environment: " . ENVIRONMENT . "<br>";
        echo "Base URL: " . base_url() . "<br>";
        
        echo "<h2>Session Data</h2>";
        $session = \Config\Services::session();
        echo "<pre>";
        print_r($session->get());
        echo "</pre>";
        
        echo "<h2>Database Connection</h2>";
        try {
            $db = db_connect();
            echo "Database connected: YES<br>";
            
            $builder = $db->table('members');
            $builder->select('COUNT(*) as count');
            $result = $builder->get()->getRowArray();
            echo "Total members: " . $result['count'] . "<br>";
        } catch (\Exception $e) {
            echo "Database error: " . $e->getMessage() . "<br>";
        }
        
        echo "<h2>POST Data</h2>";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        
        echo "<h2>GET Data</h2>";
        echo "<pre>";
        print_r($_GET);
        echo "</pre>";
        
        echo '<br><a href="' . base_url('dev-login') . '">Back to Dev Login</a>';
    }
    
    public function index()
    {
        // Check if we're in development mode
        if (ENVIRONMENT !== 'development') {
            die('This feature is only available in development mode.');
        }
        
        $session = \Config\Services::session();
        $modelMembers = new MembersModel();
        $modelProfiles = new ProfilesModel();
        $modelPermission = new PermissionModel();
        
        // Get action
        $action = $this->request->getGet('action');
        
        if ($action === 'logout') {
            $session->destroy();
            return redirect()->to(base_url('DevLogin'));
        }
        
        // If form submitted
        if ($this->request->getMethod() === 'post') {
            $bid = $this->request->getPost('bid');
            
            if ($bid) {
                try {
                    // Get member data
                    $member = $modelMembers->db_m_member($bid);
                    
                    if ($member) {
                        // Get permissions
                        $permissionSet = $modelPermission->getUserPermissionNames($bid);
                        
                        // Set session data (same as OAuth does)
                        $newdata = [
                            'mloggedin'  => $member['bid'],
                            'mloggedinName' => ucwords($member['name']),
                            'email' => $member['email'],
                            'capabilities' => $permissionSet,
                            'dsfPicture' => '',
                            'xadmin' => $member['admin']
                        ];
                        
                        $session->set($newdata);
                        
                        // Update last activity
                        $modelMembers->lastactivityUpdate($member['bid']);
                        
                        // Redirect to appropriate page
                        $redirect = $this->request->getGet('redirect');
                        if ($redirect) {
                            return redirect()->to($redirect);
                        }
                        
                        // Redirect to dev-login to show success
                        return redirect()->to(base_url('dev-login') . '?success=1');
                    } else {
                        // Member not found
                        $error = 'Member not found (BID: ' . $bid . ')';
                    }
                } catch (\Exception $e) {
                    $error = 'Error: ' . $e->getMessage();
                }
            } else {
                $error = 'No user selected';
            }
        }
        
        // Check for success message
        $success = $this->request->getGet('success');
        
        // Get all members for dropdown
        $db = db_connect();
        $builder = $db->table('members');
        $builder->join('baptism', 'members.bid = baptism.id', 'inner');
        $builder->select('members.bid, CONCAT(baptism.fName," ",baptism.lName) as name, members.admin, baptism.email');
        $builder->where('members.status', 1);
        $builder->orderBy('members.admin', 'DESC');
        $builder->orderBy('baptism.fName', 'ASC');
        $members = $builder->get()->getResultArray();
        
        // Check if already logged in
        $currentUser = null;
        if ($session->get('mloggedin')) {
            $currentUser = $modelMembers->db_m_member($session->get('mloggedin'));
        }
        
        // Display form
        echo $this->renderLoginForm($members, $currentUser, $error ?? null, $success ?? null);
    }
    
    private function renderLoginForm($members, $currentUser, $error = null, $success = null)
    {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Development Login</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
                    max-width: 600px;
                    margin: 50px auto;
                    padding: 20px;
                    background: #f5f5f5;
                }
                .container {
                    background: white;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #333;
                    margin-bottom: 10px;
                }
                .warning {
                    background: #fff3cd;
                    border: 1px solid #ffc107;
                    color: #856404;
                    padding: 12px;
                    border-radius: 4px;
                    margin-bottom: 20px;
                }
                .current-user {
                    background: #d4edda;
                    border: 1px solid #c3e6cb;
                    color: #155724;
                    padding: 15px;
                    border-radius: 4px;
                    margin-bottom: 20px;
                }
                .form-group {
                    margin-bottom: 20px;
                }
                label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: 600;
                    color: #555;
                }
                select, button {
                    width: 100%;
                    padding: 12px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    font-size: 14px;
                }
                select {
                    cursor: pointer;
                }
                button {
                    background: #007bff;
                    color: white;
                    border: none;
                    cursor: pointer;
                    font-weight: 600;
                    margin-top: 10px;
                }
                button:hover {
                    background: #0056b3;
                }
                .logout-btn {
                    background: #dc3545;
                }
                .logout-btn:hover {
                    background: #c82333;
                }
                .links {
                    margin-top: 20px;
                    padding-top: 20px;
                    border-top: 1px solid #eee;
                }
                .links a {
                    display: inline-block;
                    margin-right: 15px;
                    color: #007bff;
                    text-decoration: none;
                }
                .links a:hover {
                    text-decoration: underline;
                }
                .user-info {
                    font-size: 14px;
                    line-height: 1.6;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>🔧 Development Login</h1>
                
                <div class="warning">
                    <strong>⚠️ Development Mode Only</strong><br>
                    This login bypass is only available in development mode and should be removed in production.
                </div>
                
                <?php if ($error): ?>
                    <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                        <strong>❌ Error:</strong> <?= esc($error) ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                        <strong>✅ Success!</strong> You are now logged in.
                    </div>
                <?php endif; ?>
                
                <?php if ($currentUser): ?>
                    <div class="current-user">
                        <div class="user-info">
                            <strong>Currently Logged In:</strong><br>
                            <strong>Name:</strong> <?= esc($currentUser['name']) ?><br>
                            <strong>Email:</strong> <?= esc($currentUser['email']) ?><br>
                            <strong>BID:</strong> <?= esc($currentUser['bid']) ?><br>
                            <strong>Admin Level:</strong> <?= esc($currentUser['admin']) ?>
                        </div>
                        <form method="get" style="margin-top: 15px;">
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                    
                    <div class="links">
                        <strong>Quick Links:</strong><br>
                        <a href="<?= base_url() ?>">Home</a>
                        <a href="<?= base_url('xAdmin') ?>">Admin Dashboard</a>
                        <a href="<?= base_url('member') ?>">Member Area</a>
                        <a href="<?= base_url('pto') ?>">PTO</a>
                    </div>
                <?php else: ?>
                    <?php if (empty($members)): ?>
                        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                            <strong>❌ No Members Found</strong><br>
                            Could not load member list. Check database connection.
                            <br><br>
                            <a href="<?= base_url('DevLogin/debug') ?>" style="color: #721c24; text-decoration: underline;">View Debug Info</a>
                        </div>
                    <?php else: ?>
                        <form method="post" id="loginForm">
                            <div class="form-group">
                                <label for="bid">Select User to Login As:</label>
                                <select name="bid" id="bid" required>
                                    <option value="">-- Select a Member (<?= count($members) ?> available) --</option>
                                    <?php foreach ($members as $member): ?>
                                        <option value="<?= esc($member['bid']) ?>">
                                            <?= esc($member['name']) ?> 
                                            (Admin: <?= esc($member['admin']) ?>) 
                                            - <?= esc($member['email']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit">Login as Selected User</button>
                        </form>
                    <?php endif; ?>
                    
                    <div class="links">
                        <strong>After login, you can access:</strong><br>
                        <a href="<?= base_url() ?>">Home</a>
                        <a href="<?= base_url('xAdmin') ?>">Admin Dashboard</a>
                        <a href="<?= base_url('member') ?>">Member Area</a>
                        <a href="<?= base_url('pto') ?>">PTO</a>
                    </div>
                <?php endif; ?>
                
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 12px;">
                    <a href="<?= base_url('DevLogin/debug') ?>" style="color: #666;">🐛 View Debug Information</a>
                </div>
            </div>
            
            <script>
                // Debug: Check form submission
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.querySelector('form');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            const select = document.getElementById('bid');
                            if (!select || !select.value) {
                                alert('Please select a user first!');
                                e.preventDefault();
                                return false;
                            }
                            console.log('Form submitting with BID:', select.value);
                            // Form will submit normally
                        });
                    }
                });
            </script>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}

