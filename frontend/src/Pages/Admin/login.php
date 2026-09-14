<!DOCTYPE html>
<html lang="ht">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antre nan Panel Admen - konektem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?= BASE_URL?>/static/css/toast-notification.css" rel="stylesheet" />
    <script src="<?= BASE_URL?>/static/js/toast-notification.js" defer></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .login-header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 40px;
        }
        .btn-primary {
            background: #3498db;
            border: none;
            padding: 12px;
        }
        .btn-primary:hover {
            background: #2980b9;
        }
        /* Home Link */
        #home-link {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 100;
        }

        #home-link a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        #home-link a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            background: white;
        }

        #home-link i {
            color: #333;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div id='home-link'>
        <a href="<?= BASE_URL?>/">
            <i class="fa-solid fa-house"></i>
        </a>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card">
                    <div class="login-header">
                        <h2><i class="fas fa-lock"></i> Antre nan Panel Admen</h2>
                        <p class="mb-0">konektem Admin Panel</p>
                    </div>
                    <div class="login-body">
                        <?php if (isset($_GET['timeout'])): ?>
                            <div class="alert alert-warning">Sesyon ou fin ekspire. Tanpri antre ankò.</div>
                        <?php endif; ?>
                        
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Emel</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required 
                                           placeholder="antre@imèl.ou">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Modpas</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required 
                                           placeholder="password">
                                </div>
                            </div>
                            <input name="role" value="admin" hidden/>
                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Antre
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i> Zòn admen an sèlman
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let form = document.querySelector('form');
        const submitBtn = document.querySelector('button[type="submit"]');
        form.addEventListener('submit', async function(e){
            e.preventDefault();
            submitBtn.disabled = true;
            let url = "<?= BASE_URL ?>/auth/admin/login";
            let formData = new FormData(this);
            //formData.append('role', 'admin');

            try {
                let response = await fetch(url, {
                    method: 'POST',
                    body: formData
                });
                let result = await response.json();
                if(!result.success){
                    showError(result.message);
                    submitBtn.disabled = false;
                } else {
                    //sessionStorage.setItem('user', JSON.stringify(result.user ?? '{}'));
                    localStorage.setItem('token', result.token ?? '');
                    showSuccess(result.message ?? 'login successful');
                    setTimeout(()=>{
                        location.href = "<?= BASE_URL?>/admin";
                    }, 500);
                }
            } catch(e){
                console.error(e);
            }
        });
    </script>
</body>
</html>