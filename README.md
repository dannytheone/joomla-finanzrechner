# Planafolio Finanzrechner for Joomla

Free German finance calculators for Joomla 5 and 6: ETF savings plan (Sparplanrechner), compound interest (Zinseszinsrechner), capital gains tax (Kapitalertragsteuer), Vorabpauschale, pension gap (Rentenlücke), withdrawal, FIRE and more. Built for German investors and German tax rules. No account, no API key.

## Features

- 19 calculators, as a **module** for any template position and as **`{planafolio …}`** inside articles
- Two-click privacy mode, on by default: no connection to planafolio.com until the visitor clicks
- Light and dark theme, German and English calculators (follows the site language by default)
- The calculator adjusts its height automatically
- Optional source link "Calculator provided by Planafolio", off by default
- Joomla 5 and 6, PHP 8.1+

## Installation

Download `pkg_planafolio_finanzrechner_<version>.zip` from the [releases](https://github.com/dannytheone/joomla-finanzrechner/releases) and install it under System → Install → Extensions. The package contains the module and the content plugin; the plugin is enabled automatically. Updates arrive through Joomla's update manager.

## Usage

**Module:** Content → Site Modules → New → *Planafolio Finanzrechner*, pick the calculator, theme and language, assign a position.

**In articles:**

```
{planafolio sparplanrechner}
{planafolio zinseszinsrechner theme="dark"}
{planafolio vorabpauschale-rechner lang="en" height="900"}
```

Defaults for theme, language, two-click mode and source link are set in the plugin *Content - Planafolio Finanzrechner*.

Calculator names: `sparplanrechner`, `zinseszinsrechner`, `kapitalertragsteuer-rechner`, `vorabpauschale-rechner`, `freistellungsauftrag-rechner`, `dividenden-rechner`, `etf-kosten-rechner`, `entnahmerechner`, `finanzielle-freiheit-rechner`, `rentenluecke-rechner`, `altersvorsorgedepot`, `fruehstart-rente-rechner`, `inflationsrechner`, `einmalanlage-oder-sparplan`, `tilgen-oder-investieren`, `rebalancing-rechner`, `musterportfolios`, `xirr-rechner`, `ttwror-rechner`.

## External service and privacy

The calculators run on planafolio.com and are embedded as an iframe. When a visitor's browser loads a calculator (immediately, or after the click in two-click mode), it requests `https://planafolio.com/embed/<calculator>`; like any web request this transmits the visitor's IP address, user agent and the referring page. Values typed into the calculator are processed in the browser and only sent if the visitor actively shares a result or saves it in Planafolio. The calculator reports anonymous usage counts (calculator name and the hostname of your site) without cookies or device storage. The extension sends no data from your Joomla backend. The service is free and needs no account.

- Privacy policy: https://planafolio.com/datenschutz
- Terms: https://planafolio.com/agb

The calculations are models for illustration, not investment or tax advice.

## Build

`./build.sh` creates `dist/pkg_planafolio_finanzrechner_<version>.zip` and writes its SHA-256 into `updates.xml`.

## License

GPL-2.0-or-later
