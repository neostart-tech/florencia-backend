<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue chez FLORENCIA</title>
    <link
        href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;600&family=Raleway:wght@400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            background-color: #F3F3F3;
            margin: 0;
            padding: 0;
            color: #343434;
        }

        .welcome-email {
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            border: 1px solid #E7E7E7;
            border-radius: 8px;
            background-color: #FFFFFF;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .welcome-email h1 {
            font-family: 'EB Garamond', serif;
            font-size: 28px;
            color: #8C6239;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .welcome-email p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #676767;
        }

        .welcome-email a {
            color: #8C6239;
            text-decoration: none;
            font-weight: 600;
        }

        .welcome-email a:hover {
            text-decoration: underline;
        }

        .cta-button {
            display: inline-block;
            background-color: #8C6239;
            color: #FFFFFF;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
        }

        .cta-button:hover {
            background-color: #B17951;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 150px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #9B9B9B;
            border-top: 1px solid #E7E7E7;
            padding-top: 20px;
        }

        .signature {
            font-family: 'EB Garamond', serif;
            font-style: italic;
            color: #8C6239;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="welcome-email">
        <div class="logo">
            <img src="{{ asset('logo-cafe.png') }}" alt="Logo FLORENCIA">
        </div>
        <h1>Bienvenue chez FLORENCIA</h1>
        <p>
            Chère {{ $user->nom }},<br><br>
            Nous sommes ravis de vous accueillir au sein de l’univers <strong>FLORENCIA</strong>, un espace dédié à la
            beauté, à l’élégance et à la confiance en soi.
            Votre compte a été créé avec succès, et vous pouvez désormais accéder à nos services et produits exclusifs,
            conçus pour sublimer votre beauté naturelle.
        </p>
        <p>
            Découvrez dès maintenant notre gamme de perruques, mèches, produits cosmétiques et soins des ongles, pensés
            pour vous offrir une expérience unique et valorisante.
        </p>
        <a href="{{ env('FRONTEND_URL') }}" class="cta-button" style="color: white;">Accéder à mon compte</a>
        <p class="signature">
            Avec toute notre élégance,<br>
            L’équipe FLORENCIA
        </p>
        <div class="footer">
            <p>Besoin d’aide ou de conseils ? Notre équipe est à votre disposition.</p>
            <p>© {{ date('Y') }} FLORENCIA. Tous droits réservés.</p>
        </div>
    </div>
</body>

</html>
