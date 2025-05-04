<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Airdrop Jager</title>
    <link rel="icon" href="https://ibb.co.com/v48bxq47" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-bg: black;
            --color-text: #00ffff;
            --color-accent: yellow;
            --color-input-bg: #111;
            --color-input-text: #00ff00;
            --color-border: #00ffff;
            --color-button-bg: orange;
            --color-button-hover: #ffcc00;
            --color-result-bg: rgba(0, 0, 0, 0.8);
            --font-main: 'Press Start 2P', cursive;
        }

        html, body {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--color-bg);
            background-image: url('https://jager.meme/images/layout/bg-2.png');
            background-size: cover;
            font-family: var(--font-main);
            color: var(--color-text);
        }

        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1rem;
            width: 100%;
        }

        h1 {
            color: var(--color-accent);
            font-size: 3rem;
            text-align: center;
            margin: 1rem 0;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 700px;
        }

        textarea {
            width: 100%;
            height: 200px;
            background-color: var(--color-input-bg);
            color: var(--color-input-text);
            border: 5px solid var(--color-border);
            padding: 10px;
            font-family: monospace;
            margin-bottom: 1rem;
            resize: vertical;
        }

        .retro-button {
            background-color: var(--color-button-bg);
            color: black;
            border: 2px dashed black;
            font-family: var(--font-main);
            padding: 10px 20px;
            cursor: pointer;
            box-shadow: 4px 4px 0 #000;
            transform: rotate(-1.5deg);
            transition: background-color 0.3s ease;
        }

        .retro-button:hover {
            background-color: var(--color-button-hover);
        }

        .result {
            background-color: var(--color-result-bg);
            border: 5px solid var(--color-border);
            padding: 2rem;
            margin-top: 2rem;
            width: 100%;
            max-width: 700px;
            white-space: pre-wrap;
            border-radius: 8px;
        }

        .result-entry {
            margin-bottom: 1.5rem;
        }

        .result-entry p {
            margin: 0.3rem 0;
            line-height: 1.4;
        }

        img {
            max-width: 200px;
            margin-bottom: 1rem;
        }

        .retro-footer {
            padding: 1rem;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.85);
            color: var(--color-accent);
            font-size: 0.75rem;
            border-top: 3px dashed var(--color-border);
            width: 100%;
        }

        .error-message {
            color: cyan;
            background-color: rgba(255, 0, 0, 0.2);
            padding: 1rem;
            margin-top: 1rem;
            border: 3px dashed red;
            text-align: center;
            font-size: 0.8rem;
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 0.8rem;
            }

            .retro-button {
                font-size: 0.6rem;
                padding: 8px 16px;
            }

            textarea {
                height: 150px;
            }

            img {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <img src="https://jager.meme/_next/image?url=%2Fimages%2Fcommon%2Flogo.png&w=1920&q=75" alt="Jager Logo">
        <h1>Jager Airdrop Checker</h1>
        <form method="POST">
            <textarea name="addresses" placeholder="Enter one address per line..."></textarea>
            <button type="submit" class="retro-button">🚀 Check</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['addresses'])) {
            $rawInput = trim($_POST['addresses']);
            $addresses = array_filter(array_map('trim', explode("\n", $rawInput)));
            $total = count($addresses);

            if ($total > 10) {
                echo '<div class="error-message">⚠️ You entered ' . $total . ' addresses. Maximum allowed is 10.</div>';
            } else {
                echo '<div class="result">';
                foreach ($addresses as $address) {
                    $url = 'https://api.jager.meme/api/airdrop/queryAirdrop/' . $address;
                    $response = @file_get_contents($url);
                    echo '<div class="result-entry">';
                    if ($response === FALSE) {
                        echo "<p>❌ <strong>$address</strong>: Error connecting to API</p>";
                    } else {
                        $data = json_decode($response, true);
                        if (isset($data['data'])) {
                            $claimed = $data['data']['claimed'] ? '✅ Claimed' : '❌ Not Claimed';
                            $canAirdrop = $data['data']['canAirdrop'] ? '🎁 Eligible' : '🚫 Not Eligible';
                            $rewardRaw = $data['data']['claimInfo']['receiveAmount'] ?? $data['data']['reward'] ?? '0';
                            $reward = number_format((float)$rewardRaw, 0, '.', ',');
                            $tokenSymbol = "JAGER";
                            echo "<p><strong>$address</strong></p>";
                            echo "<p>Status: $claimed, $canAirdrop</p>";
                            echo "<p>Reward: $reward $tokenSymbol</p>";
                        } else {
                            echo "<p>⚠️ <strong>$address</strong>: Invalid response</p>";
                        }
                    }
                    echo '</div>';
                }
                echo '</div>';
            }
        }
        ?>
    </div>
    <div class="retro-footer">
        &copy; <?= date('Y') ?> WISSA GAMMA | Jager Airdrop Checker.
    </div>
</body>
</html>
