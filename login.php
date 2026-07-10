<?php
/* ─────────────────────────────────────────────────────────────
   ROADBASE – Passwortschutz (nur Passwort, kein Benutzername)

   PASSWORT ÄNDERN:
   1. Neuen Hash erzeugen (bcrypt, $2y$...) – z.B. einen
      "bcrypt generator" online nutzen oder mich fragen.
   2. Den Wert von PASSWORD_HASH unten ersetzen.
   3. Zusätzlich den AUTH_TOKEN durch eine neue Zufallszeichen-
      kette ersetzen (dann werden alle bestehenden Logins ungültig)
      – WICHTIG: denselben Wert auch in der .htaccess eintragen!
   ───────────────────────────────────────────────────────────── */

// bcrypt-Hash des Passworts (Standard: "Roadbase2026!")
const PASSWORD_HASH = '$2y$10$H7YYdapRsPVGifhii7UVw.XqYwg7vVrkvIslWjM.kUZV26DwjVwZS';

// Geheimer Token – muss identisch in der .htaccess stehen!
const AUTH_TOKEN = '45463a6d791ddd6db2dfcb75f5e8bfead77e09b486e11ebb4c1ebbf6b729f23f';

// Gültigkeitsdauer des Logins in Sekunden (Standard: 12 Stunden)
const COOKIE_LIFETIME = 12 * 3600;

$fehler = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['passwort'])) {
    if (password_verify($_POST['passwort'], PASSWORD_HASH)) {
        setcookie('roadbase_auth', AUTH_TOKEN, [
            'expires'  => time() + COOKIE_LIFETIME,
            'path'     => '/',
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        header('Location: ./');   // nach Login zur Startseite
        exit;
    }
    $fehler = true;
}
?><!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>ROADBASE – Zugang</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --red: #FF2222;
      --red-btn: #C81414;
      --red-bright: #FF3333;
      --black: #0A0A0A;
      --dark: #111111;
      --dark2: #1A1A1A;
      --mid: #333333;
      --light: #AAAAAA;
      --white: #F0F0F0;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      background: var(--black);
      color: var(--white);
      font-family: 'Barlow', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    .login-box {
      background: var(--dark);
      border: 1px solid var(--mid);
      border-top: 3px solid var(--red);
      max-width: 400px;
      width: 100%;
      padding: 48px 40px;
      text-align: center;
    }
    .logo {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 2.6rem;
      letter-spacing: 3px;
      color: var(--white);
    }
    .logo span { color: var(--red); }
    .hinweis {
      color: var(--light);
      font-weight: 300;
      margin: 12px 0 32px;
      font-size: 0.95rem;
    }
    input[type="password"] {
      width: 100%;
      background: var(--dark2);
      border: 1px solid var(--mid);
      color: var(--white);
      font-family: 'Barlow', sans-serif;
      font-size: 1rem;
      padding: 14px 16px;
      outline: none;
      text-align: center;
      letter-spacing: 1px;
    }
    input[type="password"]:focus { border-color: var(--red); }
    button {
      width: 100%;
      margin-top: 16px;
      background: var(--red-btn);
      border: none;
      color: #fff;
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.3rem;
      letter-spacing: 2px;
      padding: 13px;
      cursor: pointer;
      transition: background 0.2s;
    }
    button:hover { background: var(--red-bright); }
    .fehler {
      color: var(--red);
      font-size: 0.9rem;
      margin-top: 16px;
      min-height: 1.2em;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <div class="logo">ROAD<span>BASE</span></div>
    <p class="hinweis">Dieser Bereich ist passwortgeschützt.</p>
    <form method="post" autocomplete="off">
      <input type="password" name="passwort" placeholder="Passwort" autofocus required>
      <button type="submit">Anmelden</button>
      <div class="fehler"><?php if ($fehler) echo 'Falsches Passwort – bitte erneut versuchen.'; ?></div>
    </form>
  </div>
</body>
</html>
