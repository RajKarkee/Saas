<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Restaurant SaaS — Manage your restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        < !doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Restaurant SaaS — Manage your restaurant</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"><style> :root {
            --accent: #4f46e5;
            --accent-2: #7c3aed;
            --muted: #6b7280;
            --bg: #fbfbff
        }

        * {
            box-sizing: border-box
        }

        body {
            background: var(--bg);
            color: #0f172a;
            font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            margin: 0
        }

        .navbar {
            backdrop-filter: blur(6px)
        }

        .hero {
            padding: 6rem 0;
            position: relative;
            overflow: hidden
        }

        .hero::after {
            content: '';
            position: absolute;
            right: -10%;
            top: -10%;
            width: 60%;
            height: 120%;
            background: radial-gradient(circle at 30% 20%, rgba(79, 70, 229, 0.08), transparent 25%), linear-gradient(135deg, rgba(124, 58, 237, 0.06), transparent 40%);
            transform: rotate(15deg);
            pointer-events: none
        }

        .display-5 {
            font-weight: 800
        }

        .card-ghost {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            padding: 1.25rem
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white
        }

        .cta {
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            color: #fff;
            border-radius: 10px;
            padding: 0.85rem 1.2rem;
            font-weight: 700
        }

        .muted {
            color: var(--muted)
        }

        .pricing-card {
            border-radius: 12px;
            border: 1px solid rgba(15, 23, 42, 0.05);
            padding: 1.5rem;
            background: #fff
        }

        .testimonial {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04)
        }

        footer {
            padding: 3rem 0;
            color: var(--muted)
        }

        @media (max-width:767px) {
            .hero {
                padding: 3rem 0
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <div class="me-2 feature-icon"
                    style="width:36px;height:36px;border-radius:8px;background:linear-gradient(135deg,#06b6d4,#3b82f6)">
                    <i class="fas fa-utensils"></i>
                </div>
                <span class="fw-bold">Restaurant SaaS</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
                    <li class="nav-item ms-3 d-none d-lg-block"><a href="/login"
                            class="btn btn-outline-secondary btn-sm">Login</a></li>
                    <li class="nav-item ms-2 d-none d-lg-block"><a href="/register" class="btn btn-primary btn-sm">Get
                            Started</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-5 hero-title">Simplify restaurant operations. Delight customers.</h1>
                    <p class="muted mt-3 hero-sub">Manage orders, staff, menus and deliveries from one beautiful
                        interface — built for speed, clarity and scale.</p>
                    <div class="mt-4 d-flex gap-3 hero-ctas">
                        <a class="cta d-inline-flex align-items-center gap-2" href="/register"><i
                                class="fas fa-rocket"></i> Start free trial</a>
                        <a class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" href="#features"><i
                                class="fas fa-eye"></i> See features</a>
                    </div>
                    <div class="mt-4 d-flex gap-3 hero-cards">
                        <div class="card-ghost">
                            <div class="d-flex align-items-center">
                                <div class="me-3 feature-icon"
                                    style="width:44px;height:44px;background:linear-gradient(135deg,#ef4444,#f97316)"><i
                                        class="fas fa-bolt"></i></div>
                                <div>
                                    <div class="fw-semibold">Fast & Reliable</div>
                                    <div class="muted small">Optimized for busy kitchens and staff</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-ghost">
                            <div class="d-flex align-items-center">
                                <div class="me-3 feature-icon"
                                    style="width:44px;height:44px;background:linear-gradient(135deg,#06b6d4,#3b82f6)"><i
                                        class="fas fa-users"></i></div>
                                <div>
                                    <div class="fw-semibold">Team Friendly</div>
                                    <div class="muted small">Roles, permissions and shift management</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="card-ghost" style="height:420px;display:flex;align-items:center;justify-content:center">
                        <img src="https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?auto=format&fit=crop&w=900&q=60"
                            alt="app" style="max-width:100%;height:320px;object-fit:cover;border-radius:8px">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h3 class="fw-bold">Everything you need to run your restaurant</h3>
                <p class="muted">Orders, menus, staff, and delivery — all in one place.</p>
            </div>
            <div class="row g-4 feature-list">
                <div class="col-md-4">
                    <div class="card-ghost h-100">
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon"><i class="fas fa-receipt"></i></div>
                            <div>
                                <h6 class="fw-semibold">Order Management</h6>
                                <p class="muted small mb-0">Track, assign, and update orders in real-time with clarity.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-ghost h-100">
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon"><i class="fas fa-hamburger"></i></div>
                            <div>
                                <h6 class="fw-semibold">Menu & Inventory</h6>
                                <p class="muted small mb-0">Organize menu items, categories and stock levels
                                    effortlessly.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-ghost h-100">
                        <div class="d-flex align-items-start gap-3">
                            <div class="feature-icon"><i class="fas fa-truck"></i></div>
                            <div>
                                <h6 class="fw-semibold">Delivery & Drivers</h6>
                                <p class="muted small mb-0">Assign drivers, track deliveries and optimize routes.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h3 class="fw-bold">Pricing built for restaurants</h3>
                <p class="muted">Simple pricing, no surprises. Scale as you grow.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="pricing-card text-center">
                        <div class="mb-3 text-muted">Starter</div>
                        <div class="fw-bold display-6">Free</div>
                        <div class="muted small mb-3">Essential tools for small cafes</div>
                        <ul class="list-unstyled text-start mb-3">
                            <li>✔️ Basic order management</li>
                            <li>✔️ Single location</li>
                            <li>✔️ Community support</li>
                        </ul>
                        <a href="/register" class="btn btn-outline-secondary w-100">Start Free</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="pricing-card text-center border-2" style="border-color:rgba(79,70,229,0.12)">
                        <div class="mb-3 text-muted">Pro</div>
                        <div class="fw-bold display-6">$39<span class="muted small">/mo</span></div>
                        <div class="muted small mb-3">Advanced features for growing restaurants</div>
                        <ul class="list-unstyled text-start mb-3">
                            <li>✔️ Multi-location support</li>
                            <li>✔️ Staff roles & permissions</li>
                            <li>✔️ Priority support</li>
                        </ul>
                        <a href="/register" class="btn btn-primary w-100">Start Free Trial</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h3 class="fw-bold">Loved by chefs & managers</h3>
                <p class="muted">Restaurants large and small rely on our tools.</p>
            </div>
            <div id="testiCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="testimonial text-center">
                                    <p class="mb-3">“This product simplified our operations and reduced order
                                        mistakes by 40%.”</p>
                                    <div class="fw-semibold">Chef Amara — The Spice Table</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="testimonial text-center">
                                    <p class="mb-3">“Great support and a beautiful interface — our staff adopted it
                                        immediately.”</p>
                                    <div class="fw-semibold">Manager John — Harbour Café</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testiCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testiCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <section id="faq" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Frequently asked questions</h3>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAcc">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="q1">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#c1">Can I try it before paying?</button>
                            </h2>
                            <div id="c1" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body muted">Yes — we offer a free tier and a 14-day trial for Pro
                                    features.</div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="q2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#c2">Do you support multiple locations?</button>
                            </h2>
                            <div id="c2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
                                <div class="accordion-body muted">Yes — Pro plan supports multiple locations and
                                    advanced reporting.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container text-center">
            <h4 class="fw-bold">Get updates & product tips</h4>
            <p class="muted">Join our newsletter for product updates and industry insights.</p>
            <form class="row g-2 justify-content-center" style="max-width:720px;margin:0 auto;">
                <div class="col-sm-7">
                    <input type="email" class="form-control" placeholder="Enter your email">
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-primary w-100">Subscribe</button>
                </div>
            </form>
        </div>
    </section>

    <footer>
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div>
                <strong>Restaurant SaaS</strong>
                <div class="muted">Modern tools for hospitality</div>
            </div>
            <div class="muted small mt-3 mt-md-0">© {{ date('Y') }} Restaurant SaaS · <a href="#"
                    class="text-decoration-none">Privacy</a> · <a href="#"
                    class="text-decoration-none">Terms</a></div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- GSAP CDN with ScrollTrigger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script>
        gsap.registerPlugin(ScrollTrigger);

        document.addEventListener('DOMContentLoaded', function() {
            // Hero entrance animation with elegant sequencing
            const heroTL = gsap.timeline({
                defaults: {
                    ease: 'power4.out',
                    duration: 1
                }
            });

            heroTL
                .from('.navbar', {
                    y: -20,
                    opacity: 0,
                    duration: 0.8
                })
                .from('.hero-title', {
                    y: 50,
                    opacity: 0,
                    duration: 1.2
                }, '-=0.4')
                .from('.hero-sub', {
                    y: 30,
                    opacity: 0,
                    duration: 0.9
                }, '-=0.8')
                .from('.hero-ctas > *', {
                    y: 20,
                    opacity: 0,
                    stagger: 0.15,
                    duration: 0.8
                }, '-=0.6')
                .from('.hero-cards .card-ghost', {
                    y: 30,
                    opacity: 0,
                    stagger: 0.2,
                    duration: 0.9,
                    scale: 0.95
                }, '-=0.7')
                .from('.hero .card-ghost img', {
                    scale: 1.2,
                    opacity: 0,
                    duration: 1.2,
                    ease: 'power3.out'
                }, '-=1');

            // Feature icons with rotation and scale
            gsap.from('.feature-list .card-ghost', {
                scrollTrigger: {
                    trigger: '#features',
                    start: 'top 80%',
                    toggleActions: 'play none none reverse'
                },
                y: 60,
                opacity: 0,
                scale: 0.9,
                rotation: -5,
                stagger: {
                    amount: 0.4,
                    from: 'start'
                },
                duration: 1,
                ease: 'back.out(1.4)'
            });

            // Feature icons pulse animation
            gsap.from('.feature-list .feature-icon', {
                scrollTrigger: {
                    trigger: '#features',
                    start: 'top 75%',
                },
                scale: 0,
                rotation: 180,
                stagger: 0.15,
                duration: 0.8,
                ease: 'elastic.out(1, 0.5)'
            });

            // Pricing cards with 3D perspective
            gsap.from('.pricing-card', {
                scrollTrigger: {
                    trigger: '#pricing',
                    start: 'top 75%',
                    toggleActions: 'play none none reverse'
                },
                y: 80,
                opacity: 0,
                rotationX: -25,
                transformPerspective: 1000,
                stagger: 0.2,
                duration: 1.2,
                ease: 'power3.out'
            });

            // Pricing card hover effects
            document.querySelectorAll('.pricing-card').forEach(card => {
                card.addEventListener('mouseenter', () => {
                    gsap.to(card, {
                        y: -10,
                        boxShadow: '0 20px 40px rgba(15, 23, 42, 0.15)',
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
                card.addEventListener('mouseleave', () => {
                    gsap.to(card, {
                        y: 0,
                        boxShadow: '0 10px 30px rgba(15, 23, 42, 0.06)',
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
            });

            // Testimonials with slide and fade
            gsap.from('#testimonials .testimonial', {
                scrollTrigger: {
                    trigger: '#testimonials',
                    start: 'top 80%',
                },
                x: -50,
                opacity: 0,
                duration: 1,
                ease: 'power3.out'
            });

            // FAQ accordion items
            gsap.from('.accordion-item', {
                scrollTrigger: {
                    trigger: '#faq',
                    start: 'top 80%',
                },
                x: -30,
                opacity: 0,
                stagger: 0.1,
                duration: 0.8,
                ease: 'power2.out'
            });

            // Newsletter section
            const newsletterTL = gsap.timeline({
                scrollTrigger: {
                    trigger: '.py-5:last-of-type',
                    start: 'top 85%',
                }
            });

            newsletterTL
                .from('.py-5:last-of-type h4', {
                    y: 30,
                    opacity: 0,
                    duration: 0.8
                })
                .from('.py-5:last-of-type p', {
                    y: 20,
                    opacity: 0,
                    duration: 0.7
                }, '-=0.5')
                .from('.py-5:last-of-type form > *', {
                    y: 20,
                    opacity: 0,
                    stagger: 0.1,
                    duration: 0.6
                }, '-=0.4');

            // Footer animation
            gsap.from('footer', {
                scrollTrigger: {
                    trigger: 'footer',
                    start: 'top 95%',
                },
                y: 30,
                opacity: 0,
                duration: 1,
                ease: 'power2.out'
            });

            // Enhanced CTA hover effects with gradient shift
            document.querySelectorAll('.cta').forEach(btn => {
                btn.addEventListener('mouseenter', () => {
                    gsap.to(btn, {
                        scale: 1.05,
                        boxShadow: '0 10px 25px rgba(79, 70, 229, 0.4)',
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
                btn.addEventListener('mouseleave', () => {
                    gsap.to(btn, {
                        scale: 1,
                        boxShadow: '0 0 0 rgba(79, 70, 229, 0)',
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
            });

            // Smooth scroll parallax effect for hero background
            gsap.to('.hero::after', {
                scrollTrigger: {
                    trigger: '.hero',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: 1
                },
                y: 150,
                opacity: 0.3
            });

            // Section headings with split animation
            document.querySelectorAll('section h3, section h4').forEach(heading => {
                gsap.from(heading, {
                    scrollTrigger: {
                        trigger: heading,
                        start: 'top 90%',
                    },
                    y: 30,
                    opacity: 0,
                    duration: 0.8,
                    ease: 'power3.out'
                });
            });

            // Add subtle animation to all buttons on hover
            document.querySelectorAll('.btn').forEach(btn => {
                if (!btn.classList.contains('cta')) {
                    btn.addEventListener('mouseenter', () => {
                        gsap.to(btn, {
                            scale: 1.05,
                            duration: 0.2,
                            ease: 'power2.out'
                        });
                    });
                    btn.addEventListener('mouseleave', () => {
                        gsap.to(btn, {
                            scale: 1,
                            duration: 0.2,
                            ease: 'power2.out'
                        });
                    });
                }
            });

            // Animate feature icons on hover
            document.querySelectorAll('.feature-icon').forEach(icon => {
                icon.addEventListener('mouseenter', () => {
                    gsap.to(icon, {
                        scale: 1.15,
                        rotation: 5,
                        duration: 0.3,
                        ease: 'back.out(2)'
                    });
                });
                icon.addEventListener('mouseleave', () => {
                    gsap.to(icon, {
                        scale: 1,
                        rotation: 0,
                        duration: 0.3,
                        ease: 'back.out(2)'
                    });
                });
            });
        });
    </script>
</body>

</html>
</div>
