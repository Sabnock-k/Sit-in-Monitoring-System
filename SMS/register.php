<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Portal</title>
    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/all.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #D29C00 0%, #5E3B73 100%);
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
            margin: 0px;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 2;
        }

        .register-card {
            background: #f5f7fa;
            border-radius: 16px;
            box-shadow: var(--shadow-elegant);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .register-header {
            background: var(--primary-gradient);
            color: #f5f7fa;
            text-align: center;
            padding: 20px;
        }

        .register-header h2 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 1.5rem;
        }

        .register-form {
            padding: 20px;
            background: #f5f7fa;
            max-height: 70vh;
            overflow-y: auto;
        }

        .w3-input {
            border-radius: 8px;
            padding: 8px 12px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
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
            font-size: 0.9rem;
        }

        .reg-button {
            background: var(--primary-gradient);
            color: #f5f7fa;
            border-radius: 8px;
            padding: 10px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .reg-button:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .login-button:hover{
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .fas {
            margin-right: 8px;
        }

        footer {
            margin-top: auto;
            text-align: center;
            padding: 10px;
            color: black;
        }

        /* The entire scrollbar */
        ::-webkit-scrollbar {
            width: 10px; 
            height: 12px; 
        }

        /* The draggable part of the scrollbar */
        ::-webkit-scrollbar-thumb {
            background-color: #888; 
            border-radius: 10px;
            border: 2px solid #ccc;
        }

        /* The scrollbar track (background) */
        ::-webkit-scrollbar-track {
            background-color: #f1f1f1;
            border-radius: 10px;
        }

        /* The scrollbar when hovering over the thumb */
        ::-webkit-scrollbar-thumb:hover {
            background-color: #555;
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
<img src="pictures/ccs-logo.png" alt="Description of image">
<div class="register-container w3-margin">
    <div class="register-card">
        <div class="register-header">
            <h2><i class="fa fa-address-card"></i>Registration</h2>
        </div>
        <form class="register-form" method="POST" action="login.php">
                <label><i class="fas fa-id-card"></i> IDNO</label>
                <input class="w3-input w3-border" type="text" name="idno" required>

                <label><i class="fas fa-user"></i> Lastname</label>
                <input class="w3-input w3-border" type="text" name="lastname" required>

                <label><i class="fas fa-user"></i> Firstname</label>
                <input class="w3-input w3-border" type="text" name="firstname" required>

                <label><i class="fas fa-user"></i> Midname</label>
                <input class="w3-input w3-border" type="text" name="midname" placeholder="(optional)">

                <label><i class="fas fa-user"></i> Course</label>
                <select class="w3-input w3-border" name="course" required>
                    <option value="" disabled selected>-- Select Course --</option>
                    <option value="computer-science">Computer Science</option>
                    <option value="information-technology">Information Technology</option>
                </select>

                <label><i class="fas fa-user"></i> Year Level</label>
                <select class="w3-input w3-border" name="year-level" required>
                    <option value="" disabled selected>-- Select Year Level --</option>
                    <option value="first-year">1</option>
                    <option value="second-year">2</option>
                    <option value="third-year">3</option>
                    <option value="fourth-year">4</option>
                </select>

                <label><i class="fas fa-user"></i> Username</label>
                <input class="w3-input w3-border" type="text" name="username" required>

                <label><i class="fas fa-key"></i> Password</label>
                <input class="w3-input w3-border" type="password" name="password" required>

                <button class="w3-button w3-block reg-button" type="submit">
                    <i class="fas fa-sign-in-alt"></i> Register
                </button>
        </form>
        <div class="login-button w3-margin ">
            <a href="login.php" style="text-decoration: none" >Already have an Account? Click here to Login</a>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Patino, Rafael B. All rights reserved.</p>
    </footer>
</div>

<img src="pictures/uc-logo.png" alt="Description of image" width="220" height="200">

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
