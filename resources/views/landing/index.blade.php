<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kawan Cerito – Tempat Bercerita dan Didengar</title>

    {{-- Tailwind CSS CDN (ganti dengan build Tailwind jika sudah setup Vite/Mix) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Lora', 'serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f4ff',
                            100: '#e2eaff',
                            200: '#c3d3ff',
                            300: '#a0b8ff',
                            400: '#7b96f8',
                            500: '#5b74f0',
                            600: '#4356e3',
                            700: '#3644c8',
                            800: '#2d39a2',
                            900: '#283481',
                        },
                        teal: {
                            400: '#38c9b5',
                            500: '#2ab5a0',
                        },
                        soft: '#f5f7ff',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delay': 'float 6s ease-in-out 2s infinite',
                        'fade-up': 'fadeUp 0.7s ease forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-12px)'
                            },
                        },
                        fadeUp: {
                            from: {
                                opacity: '0',
                                transform: 'translateY(24px)'
                            },
                            to: {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                    },
                }
            }
        }
    </script>

    <style>
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }



        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
        }

        /* Card hover lift */
        .card-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(91, 116, 240, 0.15);
        }

        /* Stagger animation utilities */
        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        /* Nav link underline */
        .nav-link::after {
            content: '';
            display: block;
            height: 2px;
            background: #5b74f0;
            transform: scaleX(0);
            transition: transform 0.25s ease;
            border-radius: 99px;
        }

        .nav-link:hover::after {
            transform: scaleX(1);
        }

        /* Testimonial quote mark */
        .quote-mark {
            font-size: 5rem;
            line-height: 0.6;
            color: #c3d3ff;
            font-family: Georgia, serif;
        }

        /* Step connector line */
        .step-line {
            position: absolute;
            top: 28px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #c3d3ff, #a0b8ff);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #5b74f0, #38c9b5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Section fade-in on scroll (JS-driven) */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="font-sans text-slate-800 antialiased hero-bg">

    @include('components.landing.navbar')

    @include('components.landing.hero')

    @include('components.landing.features')

    @include('components.landing.footer')


    {{-- Alpine.js for accordion --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Scroll reveal --}}
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>

</body>

</html>
