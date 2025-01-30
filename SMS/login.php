<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal</title>
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/all.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #005C97 0%, #363795 100%);
            --secondary-gradient: linear-gradient(135deg, #ff6a88 0%, #ff9a8b 100%);
            --neutral-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --shadow-elegant: 0 10px 20px rgba(0,0,0,0.1), 0 6px 6px rgba(0,0,0,0.05);
        }

        @font-face {
            font-family: 'Inter';
            src: url('fonts/Inter_18pt-Regular.ttf');
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--neutral-gradient);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .login-card {
            background: #f5f7fa;
            border-radius: 16px;
            box-shadow: var(--shadow-elegant);
            overflow: hidden;
        }

        .login-header {
            background: var(--primary-gradient);
            color: #f5f7fa;
            text-align: center;
            padding: 20px;
        }

        .login-header h2 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .login-form {
            padding: 30px;
            background: #f5f7fa;
        }

        .w3-input {
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .w3-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #363795;
            transition: all 0.3s ease;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
            color: #2c3e50;
        }

        .error-message {
            color: #f44336;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .login-button {
            background: var(--primary-gradient);
            color: #f5f7fa;
            border-radius: 8px;
            padding: 12px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .login-button:hover {
            transform: translateY(-3px);
        }

        .fas {
            margin-right: 10px;
        }

        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
    </style>
</head>
<body>

<div id="particles-js"></div>

<div class="login-container">
    <div class="w3-card-4 login-card">
        <div class="login-header">
            <h2><i class="fas fa-lock"></i>User Login</h2>
        </div>
        <form class="login-form w3-container" method="POST" action="login.php">
            <label><i class="fas fa-user"></i> Username</label>
            <input class="w3-input w3-border" type="text" name="username" required>
            
            <label><i class="fas fa-key"></i> Password</label>
            <input class="w3-input w3-border" type="password" name="password" required>
            
            <button class="w3-button w3-block w3-margin-top login-button" type="submit">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
    </div>
    <footer style="text-align: center; padding: 20px; color: black;">
        <p>&copy; 2025 Patino, Rafael B. All rights reserved.</p>
    </footer>
</div>

<script src="particles.js-master\particles.min.js"></script>
<script>
    particlesJS("particles-js", {
        "particles": {
            "number": {
                "value": 80,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": "#005C97"
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                },
                "image": {
                    "src": "img/github.svg",
                    "width": 100,
                    "height": 100
                }
            },
            "opacity": {
                "value": 1,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.1,
                    "sync": false
                }
            },
            "size": {
                "value": 10,
                "random": true,
                "anim": {
                    "enable": false,
                    "speed": 40,
                    "size_min": 0.1,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#667eea",
                "opacity": 0.4,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "bounce": false,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "repulse"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "grab": {
                    "distance": 400,
                    "line_linked": {
                        "opacity": 1
                    }
                },
                "bubble": {
                    "distance": 400,
                    "size": 40,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                },
                "repulse": {
                    "distance": 200,
                    "duration": 0.4
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true
    });
</script>

</body>
</html>
