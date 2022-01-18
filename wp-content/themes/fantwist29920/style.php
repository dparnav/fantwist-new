<style>

@import url('https://fonts.googleapis.com/css?family=Lato:300,400,900');

*{ -webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
html {font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;margin-top:0 !important }
body { background: #f1f4f8; color:white; font-family: 'Lato', sans-serif; font-size:100%;line-height:1.5;-webkit-font-smoothing:antialiased;margin:0; -webkit-font-smoothing: antialiased; text-rendering: optimizeLegibility;}
body.noscroll { overflow:hidden; }
a { background:transparent;text-decoration: none; transition: all .25s ease-in-out; -moz-transition: all .25s ease-in-out; -webkit-transition: all .25s ease-in-out; color:#59ABE3 }
.transition { transition: all .25s ease-in-out; -moz-transition: all .25s ease-in-out; -webkit-transition: all .25s ease-in-out }
b,strong,.strong{font-weight:700}
dfn,em,.em{font-style:italic}
hr{-moz-box-sizing:content-box;box-sizing:content-box;height:0}
p {-epub-hyphens:auto;-ms-word-break:break-word; -ms-word-wrap:break-word; word-break:break-word; -webkit-hyphens:none; -moz-hyphens:none; hyphens:none; -webkit-hyphenate-before:2;-webkit-hyphenate-after:3;hyphenate-lines:3;-webkit-font-feature-settings:liga, dlig;-moz-font-feature-settings:"liga=1, dlig=1";-ms-font-feature-settings:liga, dlig;-o-font-feature-settings:liga, dlig;font-feature-settings:liga, dlig}
article,aside,details,figcaption,figure,footer,header,hgroup,main,nav,section,summary { display:block}
audio,canvas,video{display:inline-block}
audio:not([controls]){display:none;height:0}[hidden],template{display:none}
pre {white-space:pre-wrap;margin:0}
code,kbd,pre,samp{font-family:monospace, serif;font-size:1em}
q{quotes:\201C \201D \2018 \2019}
q:before,q:after{content:none}
small,.small{font-size:75%}
nav ul,nav ol{list-style:none;list-style-image:none}
img{border:0}
textarea{overflow:auto;vertical-align:top}
table{border-collapse:collapse;border-spacing:0}
dl,menu,ol,ul,dd{margin:0}
.clearfix { clear:both }
.noselect { -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none }
.align-center { text-align:center }
.align-right { text-align:right }
.align-left { text-align: left }
.position-relative { position:relative }
.white { color:white }
.fullwidth, .full-width { width:100%; max-width:100%; }
.uppercase { text-transform: uppercase }
.centered-vertical { top: 50%; -webkit-transform: translateY(-50%); -ms-transform: translateY(-50%); transform: translateY(-50%); position: absolute }
.table { display:table }
.fixed-layout { table-layout: fixed }
.table-cell { display:table-cell }
.table-row { display: table-row }
.top { vertical-align: top }
.middle { vertical-align: middle }

.logged-in.admin-bar #wpadminbar {
	position:absolute;
	top:0;
}
.added-to-ticket {
    background-color: #8e5757;
    color: white;
    font-size: 12px;
    letter-spacing:0.5pt;
    margin-left: 20px;
    padding: 5px 15px;
    font-weight: 400;
    border-radius: 15px;
    cursor: default;
    line-height: 10px;
    position: relative;
    top: -4px;
}

.wrap {
    width: 1400px;
    max-width: 100%;
    margin: 0 auto;
}
.inner-wrap {
	width: 1170px;
    max-width: 100%;
    margin: 0 auto;
}
.page-box .inner-wrap {
	position:relative;
}
.main-wrapper {
    position: relative;
    float:left;
    width:100%;
}
a.section-grid-social {
	color:white !important;
}

/* NAVIGATION */

a.nav-request-more {
    font-size: 12px;
    display:block;
}
a.nav-request-more:hover {
	text-decoration: underline;
	color:#59ABE3 !important;
}
.at-custom-side-wrapper.addthis-smartlayers.addthis-smartlayers-desktop {
    display: none;
}
#nsl-custom-login-form-1 {
	display:none;
}
.login-box .nsl-container-buttons a {
	margin: 0 !important;
}
.show #nsl-custom-login-form-1 {
	display:block;
}
.site-header div.nsl-container .nsl-button-default span.nsl-button-label-container {
    margin: 0 10px 0 0px;
    padding: 10px 0;
    font-size: 12px;
    line-height: 20px;
    letter-spacing: 0;
}
.site-header {
	background-color: rgba(44, 145, 247, 0.9);
    height:80px;
    position:fixed;
    width:100%;
    left:0;
    top:0;
    z-index:10;
}
.nav-menu-left {
	float:left;
}
.nav-menu-right {
    float: right;
    position:relative;
}
.nav-menu-right-dots {
    position: relative;
    float: right;
    line-height: 80px;
    padding:0 1em 0 0.5em;
    transition: all .25s ease-in-out; -moz-transition: all .25s ease-in-out; -webkit-transition: all .25s ease-in-out;
    cursor: pointer;
}
.nav-menu-right:hover i.fas.fa-ellipsis-h {
	transform: rotate(90deg);
}
.nav-menu-right:hover .nav-menu-right-submenu {
	opacity:1;
	z-index:2;
	top:99%;
	right:0;
	visibility: visible;
}
.nav-menu-right-dots.hide .nav-menu-right-submenu {
	opacity:0;
	z-index:-1;
	right:-10px;
	top:97%;
}
.nav-menu-right-dots i.fas.fa-ellipsis-h {
    position: relative;
    transition: all .25s ease-in-out; -moz-transition: all .25s ease-in-out; -webkit-transition: all .25s ease-in-out;
    transform: rotate(0);
}
.slim .nav-menu-right-dots {
	line-height: 50px;
	cursor: default;
}
.slim .nav-menu-right:hover .nav-menu-right-submenu {
	background-color: rgb(31, 55, 91);
    padding: 10px 1em;
}
.site-header.slim {
	height:50px;
	background-color: rgba(44, 145, 247, 0.9);
}
.site-header .nav-top {
	line-height: 0;
	position:relative;
/* 	overflow: hidden; */
}
.nav-menu-right-submenu {
    position: absolute;
    top: 97%;
    background-color: rgb(31, 55, 91);
    line-height: 34px;
    padding: 7px 15px 7px;
    opacity: 0;
    z-index: -1;
    right: -10px;
    visibility: hidden;
}
.nav-submenu-item {
	color:white;
	display:block;
}
.nav-submenu-item:hover {
	text-decoration: underline;
}
.nav-submenu-item span {
    font-size: 11px;
    font-weight: 400;
    letter-spacing: 1pt;
    padding-left: 3px;
}
.nav-balance-wrap {
    line-height: 24px;
    font-size: 12px;
    font-weight: bold;
    text-align: right;
}
.site-header .nav-top img {
    height: 70px;
    top: 4px;
    display: inline-block;
    margin: 0;
    padding: 5px 0;
    float:left;
    opacity:0;
    position:relative;
    left:-15px;
    transition: all .25s ease-in-out; -moz-transition: all .25s ease-in-out; -webkit-transition: all .25s ease-in-out;
}
.site-header .nav-top img.show {
	opacity:1;
	left:0;
}
.site-header.slim .nav-top img {
	height:40px;
	top:5px;
	left:15px;
}
a.nav-login, a.nav-bets, a.nav-profile, a.nav-signup {
    position: relative;
    top: 0;
    bottom: 0;
    display: inline-block;
    line-height: 80px;
    color: white;
    padding: 0 10px;
    font-size: 13px;
}
.nav-bets i, .nav-profile i, .nav-lobby i, .nav-contests i, .nav-leaderboard i, .nav-balance i, .nav-howtoplay i {
	padding-right: 4px;
}
.nav-login i, .nav-signup i {
	padding-left:4px
}
.nav-bets > span {
    background-color: transparent;
    padding: 12px;
    border: 1px solid white;
    border-radius: 3px;
    transition: background-color .25s ease-in-out; -moz-transition: background-color .25s ease-in-out; -webkit-transition: background-color .25s ease-in-out;
}
.nav-bets:hover > span {
	background-color: white;
	color:rgb(44, 145, 247);
}
.nav-login > span {
	padding-right:5px;
}
.slim a.nav-login, .slim a.nav-bets, .slim a.nav-profile, .slim a.nav-signup, .slim a.nav-lobby, .slim a.nav-contests, .slim .nav-leaderboard, .slim .nav-balance, .slim .nav-howtoplay {
	line-height:50px;
}
a.nav-lobby:after, a.nav-profile:after, a.nav-contests:after, a.nav-leaderboard:after, a.nav-howtoplay:after {
    display: inline-block;
    position: absolute;
    bottom: 1px;
    left: 0;
    width: 0;
    height: 1px;
    background-color: white;
    content: ' ';
    transition: all .25s ease-in-out; -moz-transition: all .25s ease-in-out; -webkit-transition: all .25s ease-in-out;
}
.nav-balance {
    top: 0;
    bottom: 0;
    display: inline-block;
    line-height: 80px;
    color: white;
    padding: 0 5px;
    position:relative;
    font-size: 13px;
    font-weight: bold;
    transition: all .25s ease-in-out; -moz-transition: all .25s ease-in-out; -webkit-transition: all .25s ease-in-out;
}
.nav-total {
    position: fixed;
    padding: 0.25em 0;
    display: none;
    width: 151px;
    margin-left: -5px;
    padding: 0px 0.25em 0.75em 0.75em;
    line-height: 24px;
    display:none;
}
.nav-balance .balance-amount {
    font-size: 11px;
    font-weight: 400;
    letter-spacing: 1pt;
    padding-left:3px;
}
.login-box {
    position: fixed;
    top: -260px;
    right: 0;
    width: 230px;
    border-radius:3px;
    text-align: left;
    background-color: #221e3a;
    padding: 0 15px;
    opacity: 0;
    box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.7);
    transition: all .3s ease-in-out; -moz-transition: all .3s ease-in-out; -webkit-transition: all .3s ease-in-out; 
}
.login-box.show {
	opacity:1;
	top:95px;
}
.slim .login-box.show {
	top: 60px;
}
.login-box:before {
    content: "";
    position: absolute;
    top: -10px;
    color: rgb(34, 30, 58);
    left: 0;
    width: 100%;
    height: 10px;
}
.login-box::after {
    content: " ";
    position: absolute;
    bottom: 100%;
    left: 20px;
    width: 0;
    height: 0;
    margin-right: -6px;
    pointer-events: none;
    border: solid transparent;
    border-width: 6px;
    border-bottom-color: rgba(255, 255, 255, 0.8); /* rgba(34, 30, 58,0.8); */
    right: 20px;
    left: auto;
    margin-right: 0;
    margin-left: -6px;
}
.login-box label {
    display: block;
    font-size: 12px;
}
.login-box input#wp-submit {
    background-color: transparent;
    color: white;
    padding: 8px 17px;
    -webkit-appearance: none;
    font-size: 11px;
    outline:none;
    transition: all .3s ease-in-out; -moz-transition: all .3s ease-in-out; -webkit-transition: all .3s ease-in-out;
    font-weight: bold;
    cursor:pointer;
}
input#user_login, input#user_pass {
    -webkit-appearance: none;
    border: none;
    background-color: white;
    font-size: 14px;
    display: block;
    margin: 4px 0;
    padding: 5px;
    width:100%;
    color:#080425;
}
.login-box a {
    display: inline-block;
    color: white;
    font-size: 12px;
    padding: 3px 0 8px;
    font-weight: 700;
}
a.logout-link {
	color:white;
	display:block;
	font-weight:bold;
}
a.logout-link:hover {
	text-decoration: underline;
}
a.nav-lobby, a.nav-contests, a.nav-leaderboard, a.nav-howtoplay {
    position: relative;
    display: inline-block;
    line-height: 80px;
    color: white;
    padding: 0 5px;
    font-size: 14px;
    margin-left:5px;
}
a.nav-logo {
    display: inline-block;
    float: left;
    margin-right: 0;
    outline:none;
}
.lobby-recent .contest-game .contest-title {
    font-size: 14px;
    line-height: 20px;
    font-weight:700
}
.lobby-recent .contest-begins, .recent-contests .contest-begins {
    font-size: 11px;
    padding: 5px 10px;
    letter-spacing: .5pt;
    display:none;
}
.contest-status-In-Progress .team-header:after {
	display:none;
}
.lobby-recent .contest-game .contest-date {
	font-size:11px;
}
.lobby-recent .contest-game.finished .contest-date {
	display:none;
}
.user-box {
    float: right;
    text-align: right;
    font-size: 13px;
    width: auto;
    margin: 0 0 1.5em;
}
.user-box {
    width: 100%;
    padding-left:5px;
    padding-bottom: 1em;
    border-bottom: 1px solid #c3c5c9;
}
.user-box a {
    display: block;
    padding: 2px 0;
    color:#444;
}
.user-box a.edit-profile {
	display:none;
}
.user-box a:hover {
	text-decoration: underline;
}

/* BETTOR INTELLIGENCE */

.profile-grid .bettor-intelligence-wrap {
	width:25%;	
	max-width:25%;
}
.bettor-intelligence-wrap {
    float: left;
    max-width: 500px;
    margin-bottom: 2em;
}
.bettor-intelligence {
    width: 100%;
/*     border-spacing: 2px 0px; */
    text-align: center;
}
.bettor-intelligence-header {
    font-weight: bold;
    font-size: 28px;
    padding: 12px 0;
}
.bettor-intelligence .table-cell.column-header, .leaderboard-wrap .table-cell.column-header {
    text-transform: uppercase;
    letter-spacing: 0.5pt;
    font-size: 10px;
    background-color: rgb(31, 55, 91);
    padding: 5px 0;
    margin:5px 0 0;
    color:white;
}
.bettor-intelligence .column-value {
    font-weight: 400;
    font-size: 12px;
    line-height: 24px;
    padding: 3px 0;
}
.bettor-intelligence .column-value.bet-type {
    font-weight: 700;
}
.bettor-intelligence .column-value.wagered {
/*     font-weight: 100; */
}
.bettor-intelligence-wrap .section-header {
/*     margin-bottom: 2px; */
}
.section-header-menu {
    position: absolute;
    right: 0;
    bottom: 0;
    top: 0;
    display:none;
    margin: auto;
    height: 30px;
    font-size: 20px;
    cursor: pointer;
    padding:0 15px 0 30px;
}
.section-header-submenu {
    position: absolute;
    right: 0;
    width: 120px;
    font-size: 14px;
    text-align: right;
    background-color: #2984e0;
	height:0;
	overflow: hidden;
	transition: all .25s ease-in-out; -moz-transition: all .25s ease-in-out; -webkit-transition: all .25s ease-in-out;
}
.section-header-submenu.show {
	height:68px;
	padding:5px 0;
}
.section-header-submenu a {
    color: white;
    padding: 5px 15px;
    display: inline-block;
    font-size: 13px;
}
h2.section-header.projections-header {
	margin-left: 12px;
    margin-top: 0;
    font-size:18px;
    margin-bottom: 10px;
}
.contest-wrapper-teamvsteam h2.section-header.projections-header {
	margin-bottom:30px;
	margin-left:0;
	background-color:#8ba1b7;
}
.bettor-intelligence .column-total {
    font-weight: bold;
    font-size: 12px;
    padding: 5px 0;
    line-height: 24px;
}
.bettor-intelligence .table-row:nth-of-type(odd) {
    background-color: rgb(226, 231, 239);
    color: #444;
}
.bettor-intelligence .table-row:nth-of-type(even) {
    color: #444
}


/* HOME */
.how-to-play-wrap {
    color: #444;
}
.home-wrapper .parallax-window {
    min-height: 600px;
    background-color: transparent;
    position:relative;
}
.home-background-image:after {
    content: ' ';
    background-color: rgba(30, 74, 121,0.7);
    height: 100%;
    width: 100%;
    position: absolute;
    border-radius: 4px;
}
.home-section-5 .home-background-image:after {
   background-color: rgba(44, 133, 225, 0.5);
}
.home-section {
	position:relative;
}
.home-section-1 h1 {
	text-transform: uppercase;
	margin:10px 0;
	font-size: 44px;
	line-height:48px;
	font-weight:400;
	text-shadow: 1px 1px #272727;
	opacity:0;
	position:relative;
	left:-20px;
	transition: all .5s ease-in-out; -moz-transition: all .5s ease-in-out; -webkit-transition: all .5s ease-in-out;
}
.home-section-1 h1.show {
	opacity:1;
	left:0;
}
.home-section-1 h2 {
    text-transform: uppercase;
    margin: 15px 0;
    font-size: 18px;
    font-weight: 400;
    width: 100%;
    text-shadow: 1px 1px #272727;
    max-width: 750px;
}
.home-section-1 h2 strong {
	color: #61a55d;
    text-shadow: 1px 1px #2b332a;
}
.home-buttons {
	margin: 40px 0;
}
a.home-btn {
    color: white;
    padding: 15px 30px;
    display: inline-block;
    margin-right:30px;
    border-radius: 4px;
    box-shadow: 0px 1px 3px rgba(0,0,0,0.7);
    font-weight:700;
}
.profile-hero a.home-btn {
	margin-top:15px;
}
.no-image a.home-btn {
    margin-right:0;
} 
a.btn-signup {
	background:linear-gradient(0deg, rgb(44, 181, 89), rgb(54, 196, 101));
}
a.btn-share {
	background:linear-gradient(0deg, rgb(44, 181, 89), rgb(54, 196, 101));
}
a.btn-learn-more {
	background-color:#d92b4c;
}
.home-section .section-header {
	text-align: center;
	font-size:28px;
	padding:0 0 1em;
	line-height:40px;
	letter-spacing: 1pt;
	text-transform: uppercase;
}
.home-section .section-header.no-upper {
	text-transform: none;
	font-size:26px;
}
.home-section.no-image {
	background-color: #f1f4f8;
    padding: 3em 1em;
    color: #444;
}
.section-grid-tile {
    padding:1em;
	border-radius: 4px;
	position:relative;
}
footer.footer {
    background-image: url('/wp-content/uploads/2019/04/marble.jpg');
    background-size: cover;
    position:relative;
}
footer.footer:after {
    content: ' ';
    background-color: rgba(63, 155, 247,0.3);
    height: 100%;
    width: 100%;
    position: absolute;
    border-radius: 4px;
    top:0;
    left:0;
}
footer.footer .copyright {
    margin: 1em 0 0;
}
footer.footer .home-section-text {
	position: relative;
    z-index: 1;
}
.page-footer .section-grid-tile {
	display:inline-block;
}
.page-footer .home-section-text {
	padding:1em 1em 0 1em;
}
.section-image .section-grid-tile {
	width:33%;
}
.home-grid-title a, .home-grid-title {
	color:#2883df;
}
.home-section.no-image .section-grid-tile {
    background-color: white;
    position: relative;
    color: #444;
    box-shadow: rgba(45, 54, 59, 0.25) 0px 1px 4px;
}
.section-image .section-grid-tile:after {
    content: ' ';
    background-color: rgba(255, 255, 255, 0.15);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.home-section .section-grid {
    border-spacing: 15px 0;
    margin: 0 auto;
}
.home-grid-icon {
    font-size: 30px;
    color:#2883df;
}
a.section-grid-social .home-grid-icon {
    color:white;
}
.home-section-3 .home-grid-icon, .home-section-3 .home-grid-title a, .home-section-3 .home-grid-title {
    color:white;
}
.home-grid-title {
    font-size: 18px;
    letter-spacing: 1pt;
    margin: 0 0 10px;
}
.home-grid-description, .home-grid-description ul, .home-grid-description li {
	display: block;
    padding: 0;
    font-size: 13px;
    margin: 0 0 5px;
    font-weight:400;
    letter-spacing: 1pt;
}
.home-grid-count {
    font-size: 50px;
    margin: 0 0 20px;
    font-weight: 100;
}
.home-section-text {
	padding:0 1em;
}
.section-image .home-section-text.centered-vertical {
	left:0;
	right:0;
	width: 1170px;
    margin: auto;
    max-width: 100%;
}
.home-disclaimer {
    font-size: 14px;
    width: 1000px;
    margin: 15px auto 0;
    max-width: 100%;
    padding-bottom: 1em;
}

#inner-content.wrap {
	padding:80px 1em;
}

.profile-box, .signup-page, .how-to-play-page, {
	padding-top:100px;
	padding-left: 1em;
    padding-right: 1em;
}

.page-hero {
    height: 400px;
    position: relative;
    background-size: cover;
    background-position: 50%;
/*     box-shadow: 0px 1px 3px rgba(0,0,0,0.7); */
}
.league-MLB.page-hero, .league-NASCAR.page-hero {
	background-position: 0 -108%;
}
.league-NFL .contest-status-in-progress i {
/* 	display:none; */
	padding-left:4px;
}
.hero-logo {
    display: block;
    width: 65px;
}
.hero-logo img {
	width:100%;
	height:auto;
	display:block;
}
.page-hero h1 {
	margin: 0;
    font-weight: 400;
    font-size: 46px;
    text-shadow: 1px 1px #272727;
    text-transform: uppercase;
}
.page-hero .hero-details {
	z-index:1;
	padding:0 1em;
	left: 0;
    right: 0;
}
.page-hero:after {
    content: ' ';
    background-color: rgba(42, 134, 224, 0.6);
    height: 100%;
    width: 100%;
    position: absolute;
    border-radius: 4px;
}
.contest-teamvsteam-cell.team-vs-team-bet.contest-status-in-progress, .contest-teamvsteam-cell.team-vs-team-bet.contest-status-completed {
    background-color: #8aa1b7;
    color: white;
}
.contest-teamvsteam-cell.team-vs-team-bet.contest-status-in-progress strong, .contest-teamvsteam-cell.team-vs-team-bet.contest-status-completed strong {
	font-weight:400
}
.page-hero h2 {
    font-weight: 700;
    font-size: 14px;
    margin: 10px 0;
    letter-spacing: 1pt;
}
.page-box {
    background-color: #f1f4f8;
    overflow: hidden;
    padding:1em;
}
.hero-details.centered-vertical {
	top: 54%;
}

/* LOBBY */


.leaderboard-wrap .leaderboard-user strong {
    float: right;
    vertical-align: middle;
    display: inline-block;
    position: relative;
    top: 5px;
}
span.leaderboard-count {
    background-color: #242035;
    padding: 5px;
    width: 30px;
    display: inline-block;
    text-align: center;
    margin: 0 10px 0 0;
}
a.leaderboard-btn {
    position: relative;
    text-transform: uppercase;
    color: white;
    background-color: #2984e0;
    padding: 15px 25px;
    font-size: 14px;
    border-radius: 3px;
    letter-spacing: .5pt;
    margin: 20px 0 0;
    clear: both;
	display: none;
}
.lobby-sidebar {
    float: left;
    width: 300px;
    padding:1em 0 1em 1em;
}
.lobby-main {
    float: left;
    padding:1em 1em 1em 0;
    width: calc(100% - 300px);
}
.my-contests-main {
	float:left;
	padding:1em 0;
	width:100%;
}
.my-contests-main .results-text {
	display:block;
	margin-top: 5px;
    font-size: 11px;
}
.lobby-tabs, .my-contests-tabs, .how-to-play-tabs {
	border-bottom: 1px solid #519be6;
}
.lobby-tabs > ul, .my-contests-tabs > ul, .how-to-play-tabs > ul {
	padding:0;
	overflow: hidden;
	background-color:#2984e0;
}
.lobby-tabs ul li, .my-contests-tabs ul li, .how-to-play-tabs ul li {
	display:inline-block;
	cursor: pointer;
	float:left;
	border-right: 1px solid #519be6;
}
.lobby-tabs ul li a, .my-contests-tabs ul li a, .how-to-play-tabs ul li a {
    color: white;
    cursor: pointer;
    display: inline-block;
    padding: 15px;
    font-size: 13px;
    font-weight: bold;
    letter-spacing: 0.75pt;
    background-color: #2984e0;
    transition: all .5s ease-in-out;
    -moz-transition: all .5s ease-in-out;
    -webkit-transition: all .5s ease-in-out;
}
.lobby-tabs a.active, .my-contests-tabs a.active, .how-to-play-tabs a.active {
    background-color: #519be6;
}
.lobby-tabs ul li a:hover, .my-contests-tabs ul li a:hover, .how-to-play-tabs ul li a:hover {
	background-color: #519be6;
}
.contest-game .contest-left {
    width: 77px;
    padding: 15px 10px;
    cursor: pointer;
}
.contest-game .contest-left img {
	width: auto;
    display: block;
    max-height: 50px;
    height: auto;
    margin: 0 auto;
}
.contest-game {
    cursor:pointer;
    position:relative;
}
.contest-game .table {
	padding:1em 0;
}
.sidebar-section .contest-game .table {
	padding:.5em 0;
}
.lobby-games {
	padding-top:15px;
}
.lobby-games .contest-game.show:nth-of-type(even) {
/*     background-color: rgb(208, 215, 220); */
}
.page-box .lobby-main .section-header {
	text-align: left;
	margin-bottom:2px;
}
.lobby-games .contest-game {
	left: -20px;
    opacity: 0;
    overflow: hidden;
    max-height: 0;
    transition: all .25s ease-in-out;
    -moz-transition: all .25s ease-in-out;
    -webkit-transition: all .25s ease-in-out;
    box-shadow: rgba(45, 54, 59, 0.25) 0px 1px 4px;
    background-color:white;
    border-radius:3px;
}
.lobby-games .contest-game.show {
	left:0px;
	opacity:1;
	max-height:400px;
	margin: 1em 0 0;
	padding:0.25em 0;
}
.contest-game.locked:after {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(8, 4, 37, 0.6);
    content: ' ';
    display:none;
}
.contest-game.locked {
	cursor: default;
}
.contest-game .contest-right {
    padding: 0 1.5em;
}
.contest-game .contest-title {
	font-size:20px;
	line-height:24px;
	color:#444;
}
.contest-title {
	margin-bottom:3px;
}
.contest-tourney-name {
    font-size: 18px;
    font-weight: 100;
    margin: 0 0 10px;
}
.contest-game .contest-type {
    font-size: 10px;
    font-weight:700;
    text-align: center;
    line-height: 13px;
    text-transform: uppercase;
    margin: 0;
    letter-spacing: 0.75pt;
    display:none;
}
.contest-type span {
	font-size:7px;
	display:block;
}
.contest-game.contest-pga .contest-type {
	margin:10px 0 0;
}
.contest-game .contest-date {
    color: #909090;
    font-size: 12px;
    margin: 2px 0;
    letter-spacing: 0.5pt;
}
.contest-begins {
    background-color: #2984e0;
    font-size: 12px;
    padding: 5px 15px;
    display: inline-block;
    letter-spacing: 0.5pt;
    margin: 5px 0 0;
    color: white;
    text-transform: uppercase;
    font-weight: bold;
    border-radius: 5px;
}
.contest-game .contest-begins {
	font-size:10px;
}
.hero-details .contest-begins {
	box-shadow: none;
}

/* CONTEST */

.contest-status-in-progress .team-header:after {
	display:none;
}
.contest-wrapper {
	overflow: hidden;	
}
.contest-wrapper-teamvsteam .contest-team {
    width: 100%;
}

.hero-details .contest-type {
    background-color: #3a364e;
    font-size: 12px;
    padding: 5px 15px;
    font-weight:bold;
    display: inline-block;
    letter-spacing: 1pt;
    margin: 5px 0 0;
    text-transform: uppercase;
}
.hero-details .contest-type {
	display:none;
}
.hero-details .contest-begins-time {
    background-color: #2984e0;
    font-size: 12px;
    font-weight:bold;
    padding: 5px 15px;
    display: inline-block;
    letter-spacing: 1pt;
    margin: 5px 0 0;
    border-radius: 5px;
}
.hero-details .contest-status {
    display: inline-block;
}
.hero-details .contest-status span {
    display: inline-block;
    background: linear-gradient(0deg, rgb(44, 181, 89), rgb(54, 196, 101));
    font-size: 12px;
    padding: 5px 15px;
    letter-spacing: 0.5pt;
    margin: 20px 0 0;
    font-weight:bold;
    text-transform: uppercase;
    border-radius: 5px;
}
.hero-details .contest-status-closed span, .hero-details .contest-status-in-progress span, .hero-details .contest-status-finished span {
	background-color: #d92b4c;
}
.in-progress .contest-begins {
    background-color: #d9354c;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 10px;
    margin: 10px 0 0;
    opacity: 0.85;
}
.in-progress .contest-date {
	display:none;
}
.lobby-live-recent-contests {
	margin:6em 0 0;
}
.contest-sidebar-left {
    float: left;
    width: 300px;
    padding:0 1em 0 0;
    z-index:9;
}
.contest-wrapper-teamvsteam .contest-sidebar-left {
	width:300px;
}
.sidebar-section {
	margin-bottom:2em;
	clear:both;
}
.sidebar-section .contest-game {
	padding:0;
	background-color:white;
	box-shadow:rgba(45, 54, 59, 0.25) 0px 1px 4px;
	margin: 1em 0;
	border-radius: 3px;
}
.contest-main {
	float:left;
	width:calc(100% - 300px);
	padding-left: 1em;
}
.contest-wrapper-teamvsteam .contest-main {
	width:calc(100% - 300px); 
}
.contest-team-vs-team-details {
    width: 100px;
    background-color: #2984e0;
}
.contest-wrapper-teamvsteam .team-header {
	background-color:transparent;
}
.contest-wrapper-teamvsteam .team-header span {
	width:100%;
	font-size:10px;
}
.contest-sidebar-left .user-box {
	text-align: left;
}
.page-box .section-header, .team-header, .odds-header {
    background-color: #2984e0;
    font-size: 13px;
    padding: 10px 15px;
    font-weight: bold;
    display: block;
    position: relative;
    text-align: center;
    letter-spacing: 0.5pt;
    text-transform: uppercase;
}
.contest-team-wrap.contest-team-vs-wrap {
    padding: 0 10px;
}
.team-vs-team-add {
    position: absolute;
    right: 0;
    opacity:0;
    top: 17px;
    font-size: 11px;
    right: 11px;
    font-weight: 700;
    opacity: 0;
    text-transform: uppercase;
    letter-spacing: 0.25pt;
}
.team-header span.team-vs-game-count {
    display: block;
    font-size:12px;
    letter-spacing: 0.5pt;
    line-height: 22px;
    font-weight: 700;
}
.team-vs-team-wrap:nth-of-type(odd) {
	/* background-color: #181733; */
    border-bottom: 1px solid #c3c5c9;
}
.team-header span.team-vs-game-time {
    display: block;
    line-height: 28px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.5pt;
    white-space: nowrap;
}
.fantasy-point-spreads {
    text-align: center;
    font-size: 10px;
    padding: 7px 0;
    font-weight: 400;
    letter-spacing: 0.5pt;
    text-transform: uppercase;
}
.team-vs-team-wrap {
    padding: 10px 10px 13px;
    position:relative;
    display:block;
    color:white;
}
.team-vs-team-wrap i {
	opacity:0;
	padding-left:5px;
	transition: opacity .25s ease-in-out; -moz-transition: opacity .25s ease-in-out; -webkit-transition: opacity .25s ease-in-out;
}
.team-vs-team-wrap:hover i {
/* 	opacity:1; */
}
.contest-status-open-for-betting .team-vs-team-wrap {
/* 	height:60px; */
/* 	cursor:pointer; */
}
.contest-wrapper-teamvsteam .team-header span.game-number {
    font-size: 28px;
    font-weight: 700;
    letter-spacing: -0.5pt;
    padding-left: 0;
}
.contest-teamvsteam-cell {
    height: 60px;
    color: #2984e0;
    background-color: #ffffff;
    vertical-align: middle;
    line-height: 60px;
    text-align: center;
    width: 85px;
    margin: 0 auto;
    font-size: 14px;
}
.contest-status-open-for-betting .contest-teamvsteam-cell, .contest-status-open-for-betting.contest-teamvsteam-cell {
	cursor: pointer;
	box-shadow: rgba(45, 54, 59, 0.25) 0px 1px 4px;
}
.contest-status-in-progress .contest-teamvsteam-cell, .contest-status-completed .contest-teamvsteam-cell {
	-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;
}
.contest-status-open-for-betting .contest-teamvsteam-cell:hover {
    background-color: rgb(249, 249, 249);
}
.contest-team-vs-team-overunder {
	position:relative;
}
.contest-team-vs-team-moneyline {
	position:relative;
}
.contest-team-vs-team-pointspread {
    padding-left: 5px;
    position:relative;
}
h2.section-header.nfl-gameday-header {
    background-color: #8ba1b7;
    font-size:16px;
    margin-bottom:30px;
    margin-top: 1em;
}
h2.section-header.nfl-gameday-header:nth-of-type(1) {
    margin-top: 0;
}
.contest-vs-1 .contest-team-vs-team-overunder:before {
    content: 'Over/Under';
    text-transform: uppercase;
    position: absolute;
    width: 100%;
    text-align: center;
    top: -20px;
    font-size: 10px;
    color: #444;
    font-weight:bold;
}
.contest-vs-1 .contest-team-vs-team-pointspread:before {
	content: 'Point Spread';
    text-transform: uppercase;
    position: absolute;
    width: 100%;
    text-align: center;
    top: -20px;
    font-size: 10px;
    color: #444;
    font-weight:bold;
}
.contest-vs-1 .contest-team-vs-team-moneyline:before {
	content: 'Moneyline';
    text-transform: uppercase;
    position: absolute;
    width: 100%;
    text-align: center;
    top: -20px;
    font-size: 10px;
    color: #444;
    font-weight:bold;
}
.contest-team-vs-team-cell {
	letter-spacing: 0.5pt
}
.contest-team-vs-team-cell .bord {
    border-bottom: 1px solid rgb(195, 197, 201);
}
.contest-team-vs-team-teams {
    width: 450px;
    background-color:white;
    box-shadow: rgba(45, 54, 59, 0.25) 0px 1px 4px;
}
.team-vs-team-wrap {
	cursor:default;
	color:#444;
}
span.team-vs-team-name {
    font-size: 14px;
    font-weight: 900;
    padding-right:5px;
}
span.team-vs-team-pts {
    font-size: 11px;
    display:block;
}
span.team-vs-spread {
    font-weight: 700;
    font-size: 13px;
}
.team-vs-game-count {
	float:left;
	text-transform: uppercase;
}
.team-header {
	font-weight:bold;
	background-color: #2983e0;
}
.team-vs-header {
	cursor:default;
}
.team-vs-header {
    overflow: hidden;
}
.contest-team.show-overlay .team-header.team-vs-header:before {
	display:none;
}
span.team-vs-game-time {
	float:right;	
}
.heading-id {
    width: 60px;
}
.heading-contest {
    width: 330px;
}
.heading-type {
    width: 135px
}
.heading-wager {
    width: 250px;
}
.heading-status {
    width: 127px;
}
.team-header:after {
    content: '+';
    position: absolute;
    top: 13px;
    right: 10px;
    font-size: 20px;
}
.contest-wrapper-mixed .team-header:after {
	top: 3px;
}
.team-header.team-vs-header:after {
	display:none;
}
.team-header span.team-winner {
    color: #61a55d;
    margin: 0 3px;
    font-size: 14px;
}
.contest-status-locked .team-header:after, .contest-status-completed .team-header:after, .contest-status-live .team-header:after {
	display:none;
}

.team-added .team-header:after {
    content: '-';
}
.team-header .at-vs {
	font-weight:400;
	font-size:12px;
	text-transform: none;
}
.team-header span {
    font-weight: 400;
    font-size: 11px;
    padding-left: 4px;
    letter-spacing: 1pt;
}
.team-header .team-header-opp {
	font-weight:400;
	font-size:12px;
}
.odds-header {
	font-size: 12px;
	font-weight:100;
    letter-spacing: 0.75pt;
    text-transform: uppercase;
}
.contest-odds {
    margin: 10px 15px;
}
.your-wagers {
	color:#444;
    padding: 0 0 0 15px;
    font-weight: bold;
    font-size: 13px;
    margin: 0 0 1.5em;
}
.contest-wrapper-teamvsteam .your-wagers {
    padding: 0;
    color:#444;
}
.leaderboard-wrap .strong .leaderboard-username, .leaderboard-wrap .strong .leaderboard-count, .leaderboard-wrap .strong .leaderboard-balance {
    font-size: 12px;
    color: #2983e0;
}
.wager-list {
    padding: 0;
    margin: 10px 0 30px;
    font-size: 12px;
    line-height: 18px;
    color: white;
    display:none;
}
.wager-list-item.loss {
    color: #d92b4c;
}
.wager-list-item.win {
    color: #4e8c4a;;
}
.wager-list-item {
    padding: 8px 10px;
    letter-spacing: 0.5pt;
    text-align: left;
    font-weight: 700;
    color: #2984e0;
    background-color: white;
    border-bottom: 1px solid #e2e7ef;
}
.wager-list-item:first-child {
	border-top: 1px solid #e2e7ef;
}
.wager-list-item:last-child {
	border-bottom: 1px solid #e2e7ef;
}
.wager-list-item .in-the-money {
    display: inline-block;
    background:linear-gradient(0deg, rgb(44, 181, 89), rgb(54, 196, 101));
    padding: 0 4px;
    font-weight: bold;
    margin-left: 6px;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.25pt;
    color:white;
}
.show-all-wagers {
	padding-left:5px;
}
.bet-ticket-close {
	position:absolute;
	top:0;
	color:#444;
	right:0;
	font-size:20px;
}
.contest-team {
    width: 33.33%;
    float: left;
    position: relative;
    margin-bottom: 1em;
    margin-top: 1em;
}
.bet-ticket-close {
    position: absolute;
    top: 5px;
    right: 15px;
    cursor: pointer;
    z-index: 1;
    font-size: 20px;
}
.contest-box-team-vs-team .contest-team-wrap {
    margin: 0;
}
.contest-team-wrap {
    margin: 0 0 0 12px;
    z-index:99;
/*     box-shadow: rgba(45, 54, 59, 0.25) 0px 1px 4px; */
}
.contest-team.show-overlay {
	cursor: pointer;
}
.contest-odds .odds-value {
	font-weight:bold;
	letter-spacing: 1pt;
	font-size:14px;
}
.contest-positions {
	text-align: center;
    font-size: 13px;
    margin: 0;
    padding: 8px 0 0;
    background-color: white;
    color: #444;
}
.team-position {
    margin: 0;
    letter-spacing: 0.75pt;
    font-size: 12px;
    line-height:23px;
    white-space: nowrap;
    cursor:pointer;
    padding:2px 0;
    position:relative;
}
.team-position.expanded {
	font-weight:bold;
}
.team-position span {
	display:inline-block;
	position:relative;
}
.team-position span:before {
    content: '\f078';
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    right: -20px;
    top: 1px;
    font-size: 9px;
    opacity: 0;
    transition: opacity .25s ease-in-out; -moz-transition: opacity .25s ease-in-out; -webkit-transition: opacity .25s ease-in-out;
}
.position-html {
	display:none;
	font-weight:400;
    font-size: 11px;
    line-height: 19px;
    letter-spacing: 0.5pt;
}
.team-position .injury-note {
    display: inline-block;
    position:relative;
    cursor: pointer;
}
.team-position .injury-note .injury-message {
    position: absolute;
    top: 0;
    width: 300px;
    white-space: normal;
    right:0;
    background-color: rgba(255, 255, 255, 0.9);
    font-weight: bold;
    color: #080424;
    letter-spacing: 0.25pt;
    font-size: 12px;
    padding: 10px;
    border-radius: 4px;
    display:none;
    z-index:1;
}
.team-position .injury-note:hover .injury-message {
	display:block;	
}
.contest-tabs-wrap {
    margin: 0 0 2em;
    padding: 1em;
    color: white;
    background-color: white;
    box-shadow: rgba(45, 54, 59, 0.25) 0px 1px 4px;
    opacity:0;
    left:-20px;
    position:relative;
    transition: all .5s ease-in-out; -moz-transition: all .5s ease-in-out; -webkit-transition: all .5s ease-in-out;
}
.contest-wrapper.contest-wrapper-teamvsteam {
    padding: 1.5em 0;
}
.contest-tabs-wrap.show {
	opacity:1;
	left:0;
}
.contest-tab {
    text-align: center;
    max-width: 0px;
    overflow: hidden;
    transition: all .25s ease-in-out; -moz-transition: all .25s ease-in-out; -webkit-transition: all .25s ease-in-out;
}
.contest-tab.show {
	display:table-cell;
	max-width: 100%;
    padding: 1em 1em 0 0;
}
.contest-tab-teams, .contest-tab-mixed {
	display:none;
	float:left;
	padding: 1em 1em 0 0;
}
.contest-tab-teams.show, .contest-tab-mixed.show {
	display:block;
}
.contest-tab-row {
	float: left;
    clear: both;
}
.contest-tab.label-tab.show {
	padding:1em 0;
}
.contest-tab .tab-header {
    font-weight: bold;
    font-size: 12px;
    margin:5px 0;
    letter-spacing: 0.75pt;
}
.my-contests a.wager-row:nth-of-type(even) {
    background-color: #eaecec;
}
.contest-tab select {
	min-width: 200px;
    font-family: 'Lato', sans-serif;
    -webkit-appearance: none;
    outline: none;
    background-color: #d0d4d8;
    border: none;
    border-radius: 0;
    color: black;
    padding: 10px 25px 10px 10px;
    font-size: 12px;
    letter-spacing: 0.75px;
}
.wager-tab .wager-label, .wager-amount-tab .wager-amount-label, .make-a-bet-label {
    display: inline-block;
    font-size: 12px;
    margin-right: 10px;
    letter-spacing: 0.25pt;
    color: #2983e0;
    font-weight: bold;
}
.my-contests .wager-row > .table-cell strong {
/*     color: #2983e0; */
}
.tab-header.hide {
	opacity:0;
}
span.wager-step {
    font-weight: bold;
    font-size: 18px;
    padding-right: 5px;
}
.wager-descriptions {
    display: inline-block;
}
.wager-description {
    display: none;
    font-size: 12px;
    padding: 5px 10px;
    cursor:default;
    font-weight: 700;
    color: white;
    background: linear-gradient(0deg, rgb(44, 181, 89), rgb(54, 196, 101));
    letter-spacing: 0.25pt;
    margin-left: 10px;
}
.wager-description.show {
	display:block;
}
.wager-amount-tab {
    margin: 15px 0 0;
    padding-top: 25px;
    border-top: 1px solid #c3c5c9;
    padding-bottom:10px;
}
.wager-tab {
    margin: 4px 0 -12px;
    padding-bottom: 27px;
    border-bottom: 1px solid #c3c5c9;
}
.wager-tab select {
	display:inline-block;
	font-family: 'Lato', sans-serif;
    -webkit-appearance: none;
    background-color: #d0d4d8;
    border: none;
    border-radius: 0;
    color: black;
    padding: 10px 35px 10px 10px;
    font-size: 13px;
    letter-spacing: 0.75px;
}
input.wager-amount {
    -webkit-appearance: none;
    background-color: #d0d4d8;
    border: none;
    padding: 10px;
    font-size: 13px;
    letter-spacing: 0.75px;
    border-radius: 0;
    color:black;
    display:inline-block;
    font-family: 'Lato', sans-serif;
}
.contest-tabs-wrap .selectdiv {
	position:relative;
	display:inline-block;
}
.contest-tabs-wrap .selectdiv select::-ms-expand {
    display: none;
}
.contest-tabs-wrap .selectdiv:after {
	content: '▼';
    font-family: Arial;
    color: white;
    right: 8px;
    top: 9px;
    padding: 0 0 2px;
    position: absolute;
    pointer-events: none;
    font-size: 11px;
}
.nfl-gameday-header {
	width:100%;
	float:left;
	margin-bottom:2em;
}
.contest-wrapper-teamvsteam .contest-tabs-wrap .selectdiv:after {
	display:none;
}
input.submit-bet {
    -webkit-appearance: none;
    background: linear-gradient(0deg, rgb(44, 181, 89), rgb(54, 196, 101));
    border: none;
    color: white;
    padding: 10px 20px;
    margin: 0 0 0 10px;
    font-weight: 600;
    border-radius: 3px;
    opacity: 1;
    font-size: 13px;
    cursor: pointer;
    position:relative;
    outline:none;
    font-family: 'Lato', sans-serif;
}
input.submit-bet.show {
	display:inline-block;
}
input.submit-bet.disabled {
	color:#c7c7c7;
	background: #999999;
	cursor:default;
	left:0 !important;
	top:0 !important;
}
.wager-submitted-message {
    background: linear-gradient(0deg, rgb(44, 181, 89), rgb(54, 196, 101));
    padding: 10px 20px;
    font-size: 13px;
    letter-spacing: 0.5pt;
    margin-bottom: 1.25em;
}
.submit-message-icon {
    display: inline;
    font-size: 18px;
    line-height: 22px;
    padding-right: 5px;
}
.wager-submitted-message.error {
	background-color:#d92b4c;
}
.wager-submitted-message a {
	color: white;
    font-weight: bold;
	/* 	text-decoration: underline; */
}
.wager-submitted-message strong {
	text-transform: uppercase;
    font-weight: 400;
}
.total-winnings-proj {
    display: none;
    font-size: 12px;
    color:#444;
    letter-spacing: 0.5pt;
    margin-left: 11px;
}
.total-winnings-msg {
	font-size: 12px;
    letter-spacing: 0.5pt;
    margin-left: 11px;
    display:inline-block;
    text-transform: uppercase;
    color:red;
    font-weight:bold;
}
.contest-box-team-vs-team h2.section-header.projections-header:after {
    content: '(ALL TIMES PT)';
    position: relative;
    font-size: 11px;
    font-weight: 400;
    padding-left: 8px;
    letter-spacing: 0.5pt;
}
.contest-box-team-vs-team .contest-status-completed h2.section-header.projections-header:after {
	content:'';
}
.contest-box-team-vs-team .contest-status-completed .team-header span.team-vs-game-time {
	display:none;
}
.total-pts {
	font-weight: bold;
    font-size: 13px;
    background-color: #1f375b;
    padding: 4px 0;
    color: white;
    line-height: 23px;
    margin: 8px 0 0;
    padding-right: 10px;
    letter-spacing: 0.75pt;
    text-transform: uppercase;
}
.refresh-live {
    position: absolute;
    top: 10px;
    right: 14px;
    cursor: pointer;
}
.contest-wrapper-nfl .refresh-live {
	display:none;
}
.total-winnings-proj.show {
	display:inline-block;
}
input.wager-amount::placeholder {
    color: black;
    font-size:12px;
}
.make-a-bet-header {
    margin: 0 0 0.75em;
    font-weight: 700;
    font-size: 20px;
    line-height: 20px;
    padding: 0 0 12px;
    display: block;
    color: #2984e0;
}

/* MY CONTESTS */
.my-contests-grid {
	margin:1em 0;
}
.my-contests {
    display: none;
}
.my-contests.show {
	display:block;
}
.my-contests .wager-type {
    text-transform: uppercase;
}
.my-contests .table-heading {
	font-weight: 700;
    font-size: 12px;
    text-align: center;
    background: rgb(31, 55, 91);
    height: 25px;
}
.my-contests a.wager-row {
    font-size: 13px;
    line-height: 35px;
    color:#444;
    background-color:white;
    height:70px;
}
.my-contests .wager-status {
	font-weight:bold;
}
.my-contests .wager-status-loss .wager-status {
	color: #d92b4c !important;
}
.my-contests .wager-status-win .wager-status {
	color: rgb(42, 146, 76) !important;
}
.my-contests .wager-contest a {
    color: white;
    padding:10px;
}
.my-contests .wager-row > .table-cell {
    line-height: 16px;
    font-size:13px;
    text-align: center;
    padding:10px 0;
}
.my-contests .wager-row:nth-of-type(even) {
/* 	background-color: #131435; */
}
.my-contests .wager-row > .table-cell.wager-contest {
	padding:0;
}
.wager-row:nth-child(even):not('.wager-status-win') .table-cell, .wager-row:nth-child(even):not('.wager-status-loss') .table-cell {
    background-color: #131435;
}
.my-contests > .table {
	border-spacing: 2px;
}
.leaderboard-wrap .leaderboard-user {
    font-size: 13px;
    color: #444;
}
.table-cell.middle.wager-id {
    font-size: 11px;
    font-weight:700;
}
.leaderboard-wrap .table-cell {
	padding: 5px;
}
.leaderboard-wrap .leaderboard-username {
	font-size: 11px;
}
.leaderboard-wrap .strong .leaderboard-username {
/* 	text-transform: uppercase */
}
.leaderboard-wrap .leaderboard-balance {
	font-size:12px;
}
.leaderboard-wrap .leaderboard-user.strong a {
	font-size: 18px;
}
.leaderboard-wrap .leaderboard-user strong {
	float:right;
}
.leaderboard-user:nth-of-type(even) {
	background-color: #e2e7ef;
    color: #444;
}
.leaderboard-user a {
    display: block;
    padding: 6px 5px;
    color:white;
    font-size:12px;
}
.leaderboard-user a:hover {
    opacity:0.7;
}
.section-left {
	margin:0 0 2.5em;
	clear:both;
}
.section-left .contest-game {
	background-color:white;
	margin:1em .25em;
	box-shadow: rgba(45, 54, 59, 0.25) 0px 1px 4px;
}
.section-left .contest-game .contest-title {
    font-size: 14px;
    line-height: 20px;
    font-weight: bold;
}
.section-left .contest-game .contest-right {
    padding: 0 0.5em 0 0.5em;
}
.sidebar-section .contest-game .contest-right {
    padding:0 1em 0 0;
}
.contest-lines-select .select-team {
	display:none;
}
.contest-lines-select .select-team.show {
	display:block;
	font-size:13px;
}
.bet-ticket-sidebar {
    height: 334px;
    opacity: 0;
    overflow: hidden;
    position: fixed;
    width: 400px;
    bottom: -300px;
    margin: 0;
    z-index: 9;
    right: 10px;
    max-width:100%;
    box-shadow: rgba(45, 54, 59, 0.25) 0px 1px 4px;
}
.bet-ticket-sidebar.show {
	opacity:1;
	bottom:0;
}
.bet-ticket-sidebar .contest-tabs-wrap {
    box-shadow: none;
    margin: 0;
    position: absolute;
    height: 100%;
    bottom: 0;
    width: 100%;
    background-color:#f7f7f7;
}
.bet-ticket-sidebar span.added-to-ticket {
    margin: 2em 0 0;
    display: block;
    padding: 10px 1em;
    border-radius: 3px;
    text-align: center;
}
.bet-ticket-sidebar .make-a-bet-header {
    margin: 0;
    border-bottom: 1px solid #c3c5c9;
    padding: 0.5em 0;
}
.bet-ticket-sidebar .wager-amount-tab {
    margin: 0;
    padding-top: 0;
    border-top: none;
    padding-bottom: 0;
}
.bet-ticket-sidebar .contest-tab.show {
    padding: 0.5em 0 1.5em;
    width:100%;
}
.bet-ticket-sidebar select {
    width:100%;
    padding: 10px;
}
.bet-ticket-sidebar .contest-tabs-wrap .selectdiv {
	display:block;
}
.bet-ticket-sidebar .contest-tab.label-tab.show {
    padding: 1em 0 0;
}
.bet-ticket-sidebar input.wager-amount {
    display: block;
    width: 100%;
    margin: 10px 0 15px;
}
.bet-ticket-sidebar input.submit-bet {
	margin:0;
}
.section-left .contest-game .contest-left {
    width: 40px;
    padding: 5px;
}
.section-left .contest-game .contest-type, .recent-contests .contest-game .contest-type {
	font-size:10px;
	letter-spacing: 0.25pt;
	min-width:51px;
}
.section-left .contest-game .contest-date, .recent-contests .contest-game .contest-date {
	font-size:11px;
}
.footer .section-social {
	margin:0 auto;
}
.leaderboard-wrap {
    float: left;
    width: 50%;
    padding: 0 2em;
}
.leaderboard.page-box .leaderboard-wrap {
	width: 100%;
	padding:0;
}
.sidebar-section .leaderboard-wrap, .section-left .leaderboard-wrap {
	width:100%;
	padding:0;
	margin-bottom:2em;
}
.profile-grid .recent-activity, .profile-grid .biggest-wins, .profile-grid .leaderboard-wrap, .profile-grid .bettor-intelligence-wrap {
	width:25%;
	float:left;
	padding:0 0.5em;
}

/* HOW TO PLAY */

.how-to-play {
	display:none;
	margin:2em 0 0;
}
.how-to-play.active {
	display:block;
}
#at4-share, .at-share-dock-outer {
	display:none;
}

/* PROFILE PAGE */

.recent-activity .table {
    font-size: 12px;
    text-align: center;
}
.recent-activity .table-cell.table-header {
    text-transform: uppercase;
    letter-spacing: 0.5pt;
    font-size: 10px;
    background-color: #1f375b;
    padding: 5px 0;
}
.recent-activity .table-cell {
    display: table-cell;
    padding: 8px 0px;
    position:relative;
}
.recent-activity .table-cell.league, .recent-activity .table-cell.type {
	font-weight:bold;
}
.recent-activity .league-logo {
    background-size: contain;
    position: relative;
    width: 50px;
    height: 50px;
}
.recent-activity span.league-name {
    display: none;
}
.recent-activity .league-logo {
    background-size: contain;
    position: absolute;
    width: 30px;
    height: 30px;
    left: 0;
    right: 0;
    margin: auto;
    top: 0;
    bottom: 0;
}
.recent-activity .table-row:nth-of-type(even) {
    background-color: #e2e7ef;
    color:#444;
}
.recent-activity .wager-row {
	color:#444;
}

.biggest-wins .table {
    font-size: 12px;
    text-align: center;
}
.biggest-wins .table-cell.table-header {
    text-transform: uppercase;
    letter-spacing: 0.5pt;
    font-size: 10px;
    background-color: #1f375b;
    padding: 5px 0;
}
.biggest-wins .table-cell {
    display: table-cell;
    padding: 8px 0px;
    position:relative;
}
.biggest-wins .table-cell.league, .biggest-wins .table-cell.type {
	font-weight:bold;
}
.biggest-wins .league-logo {
    background-size: contain;
    position: relative;
    width: 50px;
    height: 50px;
}
.biggest-wins span.league-name {
    display: none;
}
.biggest-wins .league-logo {
    background-size: contain;
    position: absolute;
    width: 30px;
    height: 30px;
    left: 0;
    right: 0;
    margin: auto;
    top: 0;
    bottom: 0;
}
.biggest-wins .table-row:nth-of-type(even) {
    background-color: #e2e7ef;
}
.biggest-wins .table-cell.date {
/*     font-weight: 100; */
}
.biggest-wins .wager-row {
	color:#444;
}
.mobile-menu {
	display:none;
}
.no-contests {
    font-weight: bold;
    font-size: 13px;
    margin: 10px 0;
    color: #444;
}
.mycontests-no-contests {
	margin:2em auto;
	font-size:12px;
	color:#444;
	font-weight:700;
}
.profile-grid {
    overflow: auto;
    margin-bottom: 1em;
}
.view-all-wagers {
    float: right;
    color: #fff;
    font-size: 12px;
    margin: 10px 0;
    letter-spacing: .25pt;
    font-weight: 700;
    display: block;
    width: 100%;
    text-align: center;
    background-color: #2984e0;
    padding: 6px;
}

/* Ultimate Member */

.um .um-field-label {
    color: #444;
}
.um-profile {
	opacity:1;
	transition: all .25s ease-in-out;
    -moz-transition: all .25s ease-in-out;
    -webkit-transition: all .25s ease-in-out;
}
.um-profile .um-cover-e {
	max-height:433px;
}
.um .um-form {
	color:white;
}
.um-page-user .um-field-label label {
    font-size: 13px !important;
}
.um-page-register .um .um-field-label, .um-page-login .um .um-field-label {
	color:white
}
.um-page-register .um-field-checkbox-option, .um-page-register .um-field-radio-option, .um-page-login .um-field-checkbox-option, .um-page-login .um-field-radio-option, .um-page-login .um-link-alt {
	color:white !important;
	font-size: 13px;
}
.um-page-register .um input[type=submit].um-button, .um-page-register .um a.um-button, .um-page-login .um input[type=submit].um-button, .um-page-login .um a.um-button, .um-page-account .um input[type=submit].um-button, .um-page-account .um input[type=submit].um-button:focus, .um-page-account .um a.um-button, .um-page-account .um a.um-button.um-disabled:hover, .um-page-account .um a.um-button.um-disabled:focus, .um-page-account .um a.um-button.um-disabled:active, .um-page-user .um input[type=submit].um-button, .um-page-user .um input[type=submit].um-button:focus, .um-page-user .um a.um-button, .um-page-user .um a.um-button.um-disabled:hover, .um-page-user .um a.um-button.um-disabled:focus, .um-page-user .um a.um-button.um-disabled:active  {
    background: #399af6;
    color: white;
    padding: 8px 17px;
    -webkit-appearance: none;
    font-size: 13px;
    font-weight: bold !important;
    outline: none;
    transition: all .3s ease-in-out;
    -moz-transition: all .3s ease-in-out;
    -webkit-transition: all .3s ease-in-out;
    font-weight: bold;
    cursor: pointer;
    border:none !important;
    box-shadow: none;
}
.um-page-register input#um-submit-btn:hover, .um-page-register .um a.um-button.um-disabled:hover, .um .um-button.um-alt:hover, .um-page-register .um input[type=submit].um-button.um-alt:hover, .um-page-login input#um-submit-btn:hover, .um-page-login .um a.um-button.um-disabled:hover, .um .um-button.um-alt:hover, .um-page-login .um input[type=submit].um-button.um-alt:hover, .um-page-account .um input[type=submit].um-button:hover, .um-page-user input#um-submit-btn:hover, .um-page-user .um a.um-button.um-disabled:hover, .um .um-button.um-alt:hover, .um-page-user .um input[type=submit].um-button.um-alt:hover, .um-page-user .um input[type=submit].um-button:hover {
	background-color:white;
	color:#399af6;
	box-shadow: none;
}
.um-page-register a.um-toggle-gdpr {
    font-weight: bold;
    color: white;
    font-size: 11px;
    text-decoration: underline;
}
.um-page-register .um .um-form input[type=text], .um-page-register .um .um-form input[type=tel], .um-page-register .um .um-form input[type=password], .um-page-register .um .um-form textarea, .um-page-login .um .um-form input[type=text], .um-page-login .um .um-form input[type=tel], .um-page-login .um .um-form input[type=password], .um-page-login .um .um-form textarea  {
	color:black;
}
.um, .um-profile.um .um-profile-headericon a {
	color:#444;
}
.um, .um-profile.um .um-profile-headericon .um-dropdown-b a {
    color: #444;
    font-weight: bold;
    font-size: 12px;
}
.um-profile.um .um-profile-headericon .um-dropdown li a:hover {
	color:#444 !important;
	text-decoration: underline !important;
}
.um-page-register .page-title, .um-page-login .page-title {
    text-align: center;
    text-transform: uppercase;
    margin: 0.75em 0 0;
    font-size: 22px;
}
.um-profile.um .um-profile-headericon a:hover, .um-profile.um .um-profile-edit-a.active {
	color:gray;
}

.um-page-register .um .um-form input[type=text], .um-page-register .um .um-form input[type=tel], .um-page-register .um .um-form input[type=number], .um-page-register .um .um-form input[type=password], .um-page-register .um .um-form textarea, .um-page-register .um .upload-progress, .um-page-register .select2-container .um-page-register .select2-choice, .um-page-register .select2-drop, .um-page-register .select2-container-multi .select2-choices, .um-page-register .select2-drop-active, .um-page-register .select2-drop.select2-drop-above, .um-page-login .um .um-form input[type=text], .um-page-login .um .um-form input[type=tel], .um-page-login .um .um-form input[type=number], .um-page-login .um .um-form input[type=password], .um-page-login .um .um-form textarea, .um-page-login .um .upload-progress, .um-page-login .select2-container .um-page-login .select2-choice, .um-page-login .select2-drop, .um-page-login .select2-container-multi .select2-choices, .um-page-login .select2-drop-active, .um-page-login .select2-drop.select2-drop-above, .um-page-account .um .um-form input[type=text], .um-page-account .um .um-form input[type=tel], .um-page-account .um .um-form input[type=number], .um-page-account .um .um-form input[type=password], .um-page-account .um .um-form textarea, .um-page-account .um .upload-progress, .um-page-account .select2-container .um-page-account .select2-choice, .um-page-account .select2-drop, .um-page-account .select2-container-multi .select2-choices, .um-page-account .select2-drop-active, .um-page-account .select2-drop.select2-drop-above{
    border: none !important;
    border-radius: 0;
}
.um-field-checkbox-state i, .um-field-radio-state i {
	color:white;
}
.um-page-register .um-field-label label, .um-page-login .um-field-label label {
    font-size: 13px !important; 
}
.um-page-register main, .um-page-login main {
	background-color: #256db5;
    padding: 1em;
    margin: 0
}
.um-page-register #inner-content.wrap, .um-page-login #inner-content.wrap {
    padding: 80px 1em 1em;
}
.um .um-tip:hover, .um .um-field-radio.active:not(.um-field-radio-state-disabled) i, .um .um-field-checkbox.active:not(.um-field-radio-state-disabled) i, .um .um-member-name a:hover, .um .um-member-more a:hover, .um .um-member-less a:hover, .um .um-members-pagi a:hover, .um .um-cover-add:hover, .um .um-profile-subnav a.active, .um .um-item-meta a, .um-account-name a:hover, .um-account-nav a.current, .um-account-side li a.current span.um-account-icon, .um-account-side li a.current:hover span.um-account-icon, .um-dropdown li a:hover, i.um-active-color, span.um-active-color {
/*     color: white !important; */
}
.um-page-user .um-profile.um .um-name a {
	color:#444;
}
.um-page-user .page-title {
	display:none;
}
.um-page-user .um-profile-nav {
    background: #3a364e;
}
.um-page-user .um-header {
	border-bottom:none;
}
.um .um-field-group-head, .picker__box, .picker__nav--prev:hover, .picker__nav--next:hover, .um .um-members-pagi span.current, .um .um-members-pagi span.current:hover, .um .um-profile-nav-item.active a, .um .um-profile-nav-item.active a:hover, .upload, .um-modal-header, .um-modal-btn, .um-modal-btn.disabled, .um-modal-btn.disabled:hover, div.uimob800 .um-account-side li a.current, div.uimob800 .um-account-side li a.current:hover, .um-profile-nav-item a:hover {
	background:transparent !important;
}
.um-profile-nav-item a:hover {
	text-decoration: underline !important;
}
.um-account-side li a.current, .um-account-side li a.current:hover {
	background:white;
}
.um-account-side li, .um-account-side li a:hover {
	background:white;
}
.um-account i {
	color:#444
}
.um-page-user .um-profile-nav {
	display:none;
}
.um-page-account h1.page-title {
	opacity:0;
}
.um-page-account .um-account-main div.um-account-heading {
	color:#444;
}
.um-page-account .um-field-label label {
    font-size: 13px !important;
    color: #444;
}
.um-page-account .um .um-form input[type=text], .um-page-account .um .um-form input[type=tel], .um-page-account .um .um-form input[type=password], .um-page-account .um .um-form textarea {
	color:#444;
}
.um-page-account .um-account-name a {
	color:#444;
}
.um-page-user .um-meta-text {
	display:none;
}
.um-account-side li a span.um-account-icon i {
	color:#444;
}
div.um-modal .upload, .um-modal-btn {
    color: black !important;
    border: 1px solid black !important;
    font-size: 12px !important;
}
.um-page-user #inner-content.wrap {
    padding: 80px 0 0;
}
.um-page-user .um-header {
	padding:0;
}
.um-page-user #content {
	color:#444;
	width: 1400px;
	max-width: 100%;
	margin: 0 auto;
}
.um.um-profile {
	margin-bottom:0 !important;
	max-width:1170px !important;
}
.footer .home-grid-icon {
	color:white !important;
}
section.home-section.home-section-3 {
    display: none;
}

@media (max-width:900px) {
	
	.contest-wrapper-teamvsteam .contest-sidebar-left {
		display:none;
	}
	.contest-wrapper-teamvsteam .contest-main {
	    width: 100%;
	    padding-left:0;
	}
	h2.section-header.projections-header {
    	margin-left: 0;
    }
    
}

@media (max-width:767px) {
	
	.um-page-register main, .um-page-login main {
	    margin: 4em 0 0;
	}
	.site-header .nav-top img {
    	height: 56px;
    	top: 14px;
    	left:5px;
    }
	.site-header.slim .nav-top img {
	    height: 50px;
		top: 0px;
		left: 4px;
	}
	a.mobile-menu-item i {
    	font-size: 16px;
	}
	.contest-team-vs-team-teams {
		box-shadow: none;
	}
	.my-contests .wager-row > .table-cell {
		padding:0 0.5em !important;
	}
	.wager-tab {
		border-bottom:none;
	}
	.my-contests .wager-row > .table-cell {
    	line-height: 17px;
    	font-size: 12px;
    }
    .bet-ticket-sidebar .contest-tabs-wrap .selectdiv:after {
	    display:none;
    }
	.contest-team-wrap.contest-team-vs-wrap {
		border:none;
	}
	.contest-game .contest-right {
    	padding: 0 1em 0 0.5em;
	}
	.contest-wrapper.contest-wrapper-teamvsteam {
		padding:0;
	}
	.make-a-bet-header {
		font-size:22px;
	}
	.contest-team-vs-team-cell {
		padding:0 0px 0 5px;
	}
	.bet-ticket-sidebar {
		right:0;
	}
	.contest-wrapper-teamvsteam h2.section-header.projections-header {
    	margin-bottom: 0;
    }
	.wager-list-item {
		font-size:10px;
	}
	.contest-vs .team-header {
    	background-color: #2984e0;
	}
	.contest-box.page-box.wrap.contest-box-team-vs-team {
	    padding: 0;
	}
	.contest-team-vs-team-teams {
    	width: 250px;
	}
	.contest-wrapper-teamvsteam .contest-team {
	    overflow-x:scroll;
	    -webkit-overflow-scrolling: touch;
	    overflow-y: visible;
	    margin-bottom:1.5em;
	    margin-top:0;
	}
	.contest-team-vs-team-details {
    	display:none;
    }
    .team-header.team-vs-header.mobile span.team-vs-game-count, .team-header.team-vs-header.mobile span.team-vs-game-time {
    	display: inline;
    	width:auto;
    	float:left;
    	line-height:22px;
    	font-size:12px;
	}
	span.team-vs-team-name {
    	font-size: 13px; 
    }
    span.team-vs-team-pts {
    	font-size: 10px; 
    }
    .contest-box-team-vs-team h2.section-header.projections-header:after {
	    font-size:10px;
    }
    .contest-wrapper-teamvsteam h2.section-header.projections-header {
	    text-align: left;
	    letter-spacing: 0
    }
	.team-header.team-vs-header.mobile span.team-vs-game-time {
    	padding-left: 5px;
	}
    .contest-wrapper-teamvsteam .team-header span.game-number {
	    font-size:12px;
    }
    .contest-team-vs-team-details .team-header {
	    padding:5px;
    }
    .team-header span.team-vs-game-count {
	    font-size:10px;
    }
	.contest-vs-1 .contest-team-vs-team-overunder:before {
	    text-transform: uppercase;
	    position: absolute;
	    width: 100%;
	    text-align: center;
	    top: -22px;
	    font-size: 8px;
	    color: white;
	}
	.contest-vs-1 .contest-team-vs-team-pointspread:before {
	    text-transform: uppercase;
	    position: absolute;
	    width: 100%;
	    text-align: center;
	    top: -22px;
	    font-size: 8px;
	    color: white;
	}
	.contest-vs-1 .contest-team-vs-team-moneyline:before {
	    text-transform: uppercase;
	    position: absolute;
	    width: 100%;
	    text-align: center;
	    top: -22px;
	    font-size: 8px;
	    color: white;
	}
	.contest-vs-1 .contest-team-vs-team-pointspread:before {
		content:'Spread';
	}
	.contest-vs-1 .contest-team-vs-team-overunder:before {
		content:'O/U';
	}
	.contest-vs-1 .contest-team-vs-team-moneyline:before {
		content:'$ Line';
	}
	.contest-wrapper-teamvsteam h2.section-header.projections-header {
    	font-size: 16px;
    	margin:1em 0 2em;
	}
	.bet-ticket-sidebar {
		width:100%;
		box-shadow: none;
		border-top:2px solid #dee0e4;
	}
	.contest-wrapper-teamvsteam .team-header span {
		padding-left:0;
	}
	.nav-menu-right-submenu{
		width:170px;
	}
	.your-wagers {
		padding: 15px 0;
		margin: 0;
		font-size:12px;
	}
	.contest-wrapper-teamvsteam .your-wagers {
		padding:15px 1em;
	}
    .show-all-wagers {
	    font-size: 12px;
    }
    .refresh-live {
	    position: relative;
	    top: 0;
	    right: 2px;
	    float: right;
	}
	.wager-list {
   		margin: 10px 0 0px;
    }
    .contest-team-wrap {
    	margin: 0;
	}
    h2.section-header.projections-header {
    	margin-left: 0;
    }
	.wager-tab {
		padding-bottom:10px;
	}
	.um-page-user .page-template-default #inner-content.wrap {
	    padding-top: 2em;
	}
	.user-box {
		padding-right:1em;
	}
	.league-MLB.page-hero, .league-NASCAR.page-hero {
		background-position: top;
	}
	.my-contests.show {
		overflow-x:scroll;
		-webkit-overflow-scrolling: touch;
	}
	.my-contests .wager-row > .table-cell {
		min-width:160px;
	}
	.my-contests .wager-row > .table-cell.wager-status {
		min-width:120px;
	}
	.my-contests .wager-row > .table-cell.wager-id, .my-contests .wager-row > .table-cell.wager-type {
		min-width:80px;
	}
	.my-contests .wager-row > .table-cell.wager-winners {
		min-width:250px;
	}
	.nav-signup {
		margin-right: 15px;
	}
	.lobby-tabs ul li a, .my-contests-tabs ul li a, .how-to-play-tabs ul li a {
		padding:15px 12px;
	}
	a.nav-bets {
		font-size:11px;
		padding:0;
		margin-right:10px;
	}
	.leaderboard-wrap {
	    width: 100%;
	    padding: 0;
	    margin-bottom: 2em;
	}
	footer.footer {
		padding-bottom:5em;
	}
	.mobile-menu {
	    display: table;
	    position: fixed;
	    bottom: 0;
	    left: 0;
	    width: 100%;
	    z-index: 3;
	    background-color: #2984e0;
	    font-size: 11px;
	    table-layout: fixed;
	}
	a.mobile-menu-item {
	    display: table-cell;
	    vertical-align: middle;
	    text-align: center;
	    padding: 15px 0 25px;
	    color: white;
	    font-weight: bold;
	    
	}
	.mobile-menu-item span {
		display:block;
	}
	
	.contest-sidebar-left {
		display:none;
		width:100%;
		padding: 0;
		margin:1em 0;
		border-right: none;
	}
	.section-left .contest-game {
    	padding: 0;
	}
	.hero-details.centered-vertical {
		top:50%;
	}
	.page-template-default #inner-content.wrap {
		padding-top:1em;
	}
	.nav-menu-right a.nav-profile {
		display:none;
	}
	.profile-grid .recent-activity, .profile-grid .biggest-wins, .profile-grid .leaderboard-wrap, .profile-grid .bettor-intelligence-wrap {
		width:100%;
		margin-bottom:2em;
		max-width:100%;
	}
	section.home-section.home-section-5.section-image{
		padding:2em 0 0;
	}
	.my-contests-page.page-box.wrap {
	    padding: 1em 0;
	}
	.my-contests-tabs {
		padding:0;
		margin:0 1em;
	}
	.my-contests .table-heading {
		padding:0 1em;
	}
	.contest-tourney-name {
	    font-size: 14px;
	    margin: 0 0 5px;
	}
	.tab-header.hide {
		display:none;
	}
	.wager-amount-tab {
		margin:0;
	}
	.team-position .injury-note .injury-message {
		right:0;
	}
	.wager-description {
		font-size: 11px;
		padding-left: 7px;
		padding-top: 6px;
		text-align: center;
		padding-right: 7px;
		margin: 10px 0;
	}
	.contest-tab.label-tab.show {
	    padding: 1.5em 0 0.5em;
	}
	.wager-amount-tab {
		border-top:none;
	}
	.wager-amount-label {
	    padding-top: 0;
	}
	.lobby-sidebar {
		width:100%;
	}
	.my-contests .fixed-layout {
		table-layout: auto;
	}
	.my-contests .wager-row, .my-contests .table-heading {
	    font-size: 11px;
	}
	.my-contests-main {
		padding:0;
	}
	.wager-tab .wager-label, .wager-amount-tab .wager-amount-label, .make-a-bet-label {
		margin-right:0;
	}
	input.wager-amount {
		width:100%;
		display:block;
	}
	.wager-label, .wager-amount-label {
		width: 100%;
		margin: 0 0 0.5em;
	}
	.contest-tab {
		max-height: 0;
		display: inline-block;
		text-align: left;
	}
	.contest-tab.label-tab .tab-header {
		display:none;
	}
	.wager-tab select, .contest-tab select {
		width:100%;
	}
	.contest-tabs-wrap .selectdiv {
		width:100%;
	}
	.contest-tab.show {
		display:block;
		width:100%;
		max-height:100%;
		padding: 0em 0 1em 0;
	}
	.contest-tabs {
		width:100%;
		display:block;
		margin: 0;
	}
	.contest-main {
	    width: 100%;
	    padding-left: 0em;
	    padding-right:0em;
	}
	.contest-team {
    	width: 100%;
    }
    .contest-wrapper-teamvsteam .contest-team {
	    width: 100%;
	    overflow: visible;
	}
	h2.section-header.nfl-gameday-header {
		font-size: 12px;
		margin-bottom: 1em;
	}
	.contest-wrapper-teamvsteam .contest-team > .table {
		padding:0;	
		margin: 0;
	}
	.team-vs-team-wrap {
    	padding: 10px 0 13px;
    }
	.contest-teamvsteam-cell {
		font-size:11px;
	}
	.contest-teamvsteam-cell {
		width:60px;
	}
	input.submit-bet {
		display:block;
		margin: 20px 0 0;
		width: 100%;
    }
    .total-winnings-proj.show {
	    display: block;
	    text-align: center;
	    margin: 10px auto 0;
	}
	.lobby-sidebar {
		padding:0;
		margin:3.5em 0 0;
	}
	.hero-details .contest-status span {
		margin:2px 0 0;
	}
	.lobby-main {
		padding:0;
		width:100%;
	}
	a.leaderboard-btn {
	    padding: 13px 20px;
	    font-size: 12px;
	    margin: 10px 0 0;
	}
	.lobby-tabs ul li a, .my-contests-tabs ul li a, .how-to-play-tabs ul li a {
		font-size:12px;
	}
	.total-winnings-proj {
	    font-size: 12px;
	    margin-left: 0;
	    display:block;
	    color:#444;
	    margin-top: 10px;
	    text-align: center;
	}
	.contest-game .contest-left {
		width:70px;
	}
	.contest-game .contest-title {
	    font-size: 17px;
	    line-height: 24px;
	}
	.contest-game .contest-date {
		font-size:11px;	
	}
	.page-hero {
    	height: 235px; 
    }
	.page-hero h1 {
		font-size: 26px;
		line-height: 34px;
	}
	.page-hero h2 {
		font-size:12px;
	}
	.nav-balance {
		font-size:11px;
	}
	.added-to-ticket {
		margin: 15px 0 0;
	    top: 0;
	    padding: 10px;
	    text-align: center;
	    display:block;
	}
	.slim .nav-balance, .nav-balance {
		line-height: 30px;
		height: 50px;
		text-align: center;
		vertical-align: middle;
		padding:0 10px 0 0;
	}
	.section-left .contest-game .contest-right {
	    padding: 0 0 0 1em;
	}
	.nav-balance .balance-amount {
		display:block;
		line-height: 5px;
		font-size: 11px;
	}
	.home-section-text {
		top: 57%;
	}
	.home-section-1 h1 {
	    font-size: 28px;
	    line-height: 32px;
	}
	.home-section-1 h2 {
		font-size:14px;
	}
		
	a.home-btn {
		margin-bottom:10px;
		margin-right:0;
	}
	
	.home-background-1 {
		background-image: url(/wp-content/uploads/2019/03/home-background-1.jpg);
		background-size: cover;
		background-position: center;
	}
	.home-section .section-header {
		font-size:24px;
		line-height:32px;
	}
	.section-grid-tile.table-cell {
		display:block;
		margin-bottom: 10px;
	}
	#at-custom-mobile-bar {
		display:none !important;
	}
	.section-social .section-grid-tile.table-cell {
		float:left;
	}
	.home-grid-title {
		font-size:22px;
	}
	.section-image .section-grid-tile {
		width:100%;
	}
	.section-image .centered-vertical {
		position:relative;
		-webkit-transform: none;
		-ms-transform: none;
		transform: none;
	}
	.home-wrapper .section-image .parallax-window {
		min-height: auto;
	}
	.home-section.section-image {
    	padding: 2em 0;
    	color:#444
	}
	.home-section-3 .home-grid-icon, .home-section-3 .home-grid-title a, .home-section-3 .home-grid-title {
	    color: #2883df;
	}
	.home-section-5 a.section-grid-social {
    	color: #444 !important;
	}
	a.section-grid-social .home-grid-icon {
		color:#444;
	}
	.footer-links a {
		color:white;
	}
	.home .footer-links a {
		color:#444;
	}
	.section-image .section-social {
		width:100%;
	}
	.section-image .section-social .section-grid-tile {
	    width: 47%;
	    float: left;
	    margin: 0 4px 0;
	}
	.nav-menu-left {
		display:none;
	}
	.hero-details .contest-type, .hero-details .contest-begins-time, .hero-details .contest-status span, .hero-details .contest-begins {
		font-size:9px;
	}
	.nav-balance.noselect {
	    padding-top: 2px;
	    padding-right: 12px;
	}
}

@media (min-width:768px) {
	
	.nav-balance:hover {
	    cursor: pointer;
	}
	.contest-team-wrap.expanded {
		position: absolute;
	    width: 100%;
	    padding-right: 12px;
	    z-index: 9;
	}
	.team-header.team-vs-header.mobile {
		display:none;
	}
	.site-header.slim .nav-top img {
	    height: 48px;
	    top: 0px;
	    left: 0;
	    padding:0;
	}
	a.nav-logo {
		margin-right:20px;
	}	
}

@media (max-width:1024px) {
	
	.contest-status-open-for-betting .team-vs-team-add {
		opacity:1;
	}
	
	
}

@media (min-width:1025px) {
	
	/*
	.contest-team-wrap.expanded {
		position: relative;
	    width: calc(100% - 12px);
	    background-color: rgba(8, 4, 37, 1);
	}
	*/
	.contest-status-open-for-betting .team-vs-team-wrap:hover .team-vs-team-add {
		opacity:1;
		text-decoration: underline;
	}
	.leaderboard-username:hover {
		text-decoration:underline;
	}
	.team-position:hover span:before {
		opacity:1;
	}
	.team-position:hover {
		font-weight:bold;
	}
	.contest-status-open-for-betting .player-position strong {
		display:none;
	}
	.contest-status-open-for-betting .contest-wrapper-nfl .player-position strong {
		display:inline;
	}
	.contest-status-open-for-betting .player-position.empty strong {
		display:inline;
	}
	.recent-activity .wager-row:hover, .biggest-wins .wager-row:hover, .section-header-submenu a:hover{
		opacity:0.7;
	}
	.my-contests .wager-contest a:hover {
		text-decoration: underline;
	}
	.login-box input#wp-submit:hover {	
		background-color:white;
		color:#080425;
	}
	a.nav-lobby:hover:after, a.nav-profile:hover:after, a.nav-contests:hover:after, a.nav-leaderboard:hover:after, a.nav-lobby.active:after, a.nav-profile.active:after, a.nav-contests.active:after, a.nav-leaderboard.active:after, a.nav-howtoplay:hover:after, a.nav-howtoplay.active:after {
		width:100%;
	}
	.login-box a:hover {
		text-decoration: underline;
	}
	.contest-game a:hover .contest-title {
	    opacity:0.7;
	}
	input.submit-bet:active {
		top:1px;
		left:1px;
	}
	.my-contests a.wager-row:hover {
		/* color:#59ABE3; */
		opacity:0.85;
	}
	.team-position .injury-note:hover {
		color:#c5c5c5;
	}
	.contest-team.show-overlay .team-header:after {
		content: ' ';
	    position: absolute;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    background-color: rgb(31, 55, 91);
		box-sizing: border-box;
	}
	.contest-team.show-overlay .team-header:before {
		content: 'Add to ticket +';
	    left: 0;
	    width: 100%;
	    text-align: center;
	    top: 50%;
	    -webkit-transform: translateY(-50%);
	    -ms-transform: translateY(-50%);
	    transform: translateY(-50%);
	    position: absolute;
	    z-index: 9;
	    font-size: 16px;
	    text-transform: uppercase;
	    font-weight:bold;
	}
	.view-all-wagers:hover {
		text-decoration: underline;
	}
	
	
}

@media (max-width:320px) {
	
	.site-header.slim .nav-top img {
	    height: 38px;
	    top: 5px;
	    left: 5px;
	}
	a.nav-bets {
		margin-right:0
	}
	.nav-balance.noselect {
	    padding-top: 2px;
	    padding-right: 3px;
	    padding-left: 6px;
	}
	.login-box.show {
		right:15px !important;
	}
	
}

</style>