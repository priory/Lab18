<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
* {
	border: 0px solid #FF00FF;
	box-sizing: border-box;
}
html {
	border: none;
}
body {
	--aCol: <?php
	switch ($theme[0]) {
		case (0): echo '#333'; break;
		case (1): echo '#EEE'; break;
	}
	?>;
	--fCol: <?php 
	switch ($theme[0]) {
		case (0): echo '#EEE'; break;
		case (1): echo '#333'; break;
	}
	?>;
	--tCol: <?php 
	switch ($theme[1]) {
		case (0): echo '#0078d7'; break;
		case (1): echo '#ffb900'; break;
		case (2): echo '#e81124'; break;
		case (3): echo '#7a7574'; break;
		case (4): echo '#10893e'; break;
	}
	?>;
	color: var(--fCol);
	font-family: sans-serif;
	background-color: var(--aCol);
	margin: 0;
}
header {
	width: 100%;
	background-color: var(--tCol);
	position: fixed;
	top: 0px;
	height: 110px;
}
header h1 {
	width: 500px;
	font-size: 50px;
	margin-top: 20px;
	position: absolute;
	left: calc(50% - 300px);
	text-align: center;
	color: white;
	text-shadow: 1px 2px 3px #00000077;
}
footer {
	padding-top: 10px;
	width: 100%;
	height: 50px;
	background-color: var(--tCol);
	text-align: center;
	color: white;
	position: fixed;
	bottom: 0px;
	z-index: 1;
}
main {
	height: auto;
	position: absolute;
	top: 110px;
	z-index: -1;
	width: 60%;
	min-width: 350px;
	left: 50%;
	transform: translateX(-50%);
	margin-bottom: 50px;
}
#bodyBackground {
	position: fixed; 
	z-index: -5;
	width: 100%;
	width: 1920px;
	left: calc(100% - 960px - 50%);
	top: 0px;
}
#backgroundEffect {
	z-index: -2;
	height: 100vh;
	position: fixed;
	width: 60%;
	min-width: 350px;
	left: 50%;
	transform: translateX(-50%);
	background-color: var(--aCol);
	overflow: hidden;
}
#backgroundEffect div {
	width: 100%;
	height: 100%;
	background-color: var(--aCol);
	filter: opacity(60%);
}
#backgroundEffect img {
	z-index: -1;
	filter: blur(15px);
	width: 1920px;
	position: absolute;
	left: calc(100% - 960px - 50%);
	top: 0px;
}
#headerLogo {
	position: absolute;
	top: 10px;
	left: calc(50% + 150px);
}
#threadTitle {
	border: 2px solid var(--fCol);
	text-align: center;
	background-color: var(--tCol);
	color: white;
	position: sticky;
	top: 110px;
	z-index: 1;
}
#threadTitle h2 {
	margin: 10px;
}
#reaction {
	resize: none;
	position: relative;
	width: calc(100% - 252px);
	height: 100px;
	margin-left: 252px;
	margin-right: 0px;
}
/* HEADER BAR */
.menuBar {
	display: block;
	position: absolute;
	width: 100%;
	height: 110px;
}
.menuBar .label, .menuBar .button{
	position: absolute;
	color: white;
	border-color: white;
}
.menuBar .button {
	width: 120px;
	bottom: 2px;
}
.menuBar .button:hover {
	border-color: var(--tCol);
	color: var(--tCol);
	background-color: white;
}
.menuBar .label.account {
	right: 70px;
	top: 25px;
}
.menuBar .button.one {
	right: 2px;
}
.menuBar .button.two {
	right: 124px;
}
.menuBar .button.three {
	right: 246px;
}
.menuBar .button.four {
	right: 368px;
}
.stackButton {
	display: none;
	position: absolute;
	right: 20px;
	top: 20px;
	width: 75px;
	height: 75px;
	background-image: url('/lab18/resources/menuButton.png');
	background-size: contain;
}
/* THREAD LIST*/
.thread {
	border: 2px solid var(--fCol);
	position: relative;
	overflow: hidden;
	cursor: pointer;
}
.thread:hover {
	background-color: #FFFFFF66;
}
.thread .threadTitle {
	position: absolute; 
	left: 300px; 
	top: 0px; 
	width: 500px;
}
/* REACTIONS */
.reaction {
	position: relative;
	border: 2px solid var(--fCol);
	width: 100%;
}
.reaction .timeStampReact {
	position: absolute;
	left: 55px;
	top: 20px;
}
.reaction .settings {
	display: none; 
	position: absolute;
	top: 0px;
	width: 100%;
	height: 100%;
}
.reaction .name {
	position: absolute;
	left: 55px;
	top: 2px;
	overflow: hidden;
}
.reaction .message {
	width: calc(100% - 250px);
	position: relative;
	left: 250px;
	color: var(--fCol);
	min-height: 90px;
	border-left: 1px solid var(--fCol);
}
.reaction .messageEdit {
	height: 100%; 
	position: absolute; 
	top: 0px; 
	right: 0px; 
	width: calc(100% - 250px); 
	resize: none;
}
.reaction .messageEditTitle {
	height: calc(100% - 20px); 
	top: 20px;
	position: absolute; 
	right: 0px; 
	width: calc(100% - 250px); 
	resize: none;
}
.reaction .editTitle {
	position: absolute;
	left: initial;
	right: 0px;
	width: calc(100% - 250px);
}
.button {
	border: 2px solid var(--fCol);
	position: absolute;
	user-select: none;
	text-align: center;
	width: 90px;
}

.reaction .button {
	left: 2px;
	bottom: 2px;
}
.button:hover {
	border: 2px solid var(--aCol);
	color: var(--aCol);
	background-color: var(--fCol);
	cursor: pointer;
}
.reaction .cancel {
	width: 25px;
}
.reaction .change {
	
}
.reaction .send {
	left: 28px;
}
.reaction .delete {
	left: 120px;
}
.react {
	left: 252px;
}
/* LOGIN */
.panel {
	left: calc(50% - 175px);
	top: 120px;
	position: absolute;
	width: 350px;
	height: 300px;
	background-color: var(--tCol);
}
input {
	position: absolute;
	width: 50%;
	left: 25%;
	padding-left: 10px;
}
input.name {
	top: 80px;
}
input.password {
	top: 130px;
}
.panel .label {
	position: relative;
	width: 250px;
	left: 25%;
}
.panel .label.name {
	top: 60px;
}
.panel .label.password {
	top: 90px;
}
.button.login {
	left: calc(50% - 91px);
	top: 160px;
}
.button.home {
	left: 2px;
	bottom: 2px;
}
.button.register {
	left: calc(50% + 1px);
	top: 160px;
}

.panel .checkbox {
	position: absolute;
	left: -10px;
	top: 190px;
}
.panel > .button:hover {
	background-color: white;
	border: 2px solid var(--tCol);
	color: var(--tCol);
}
.panel .errors {
	position: absolute;
	background-color: #ef2b2b;
	top: 300px;
	width: 100%;
}
.remember {
	position: absolute;
	font-size: 14px;
	left: 90px;
	top: 190px;
}
/* REGISTRATION */
.registration {
	left: calc(50% - 175px);
	top: 120px;
	position: absolute;
	width: 350px;
	height: 350px;
	background-color: var(--tCol);
}
.registration .text {
	position: absolute;
}
.registration .text {
	width: 50%;
	left: 25%;
}
.registration .label {
	position: absolute;
	left: 25%;
}
.registration .text.name {
	top: 50px;
}
.registration .text.password {
	top: 100px;
}
.registration .text.repeat {
	top: 150px;
}
.registration .button.file {
	width: 150px;
	left: calc(50% - 75px);
	top: 200px;
}
.registration .button.registrate {
	width: 100px;
	left: calc(50% - 50px);
	bottom: 30px;
	height: 22px;
}
.registration .button.home {
	width: 100px;
	left: 2px;
	height: 22px;
	bottom: 2px;
}
.registration .label.name {
	top: 30px;
}
.registration .label.password {
	top: 80px;
}
.registration .label.repeat {
	top: 130px;
}
.registration .label.profile {
	top: 180px;
}
.registration .label.status {
	top: 228px;
	left: 50%;
	transform: translateX(-50%);
}
.registration > .button:hover {
	background-color: white;
	border: 2px solid var(--tCol);
	color: var(--tCol);
}
.registration .errors {
	position: absolute;
	background-color: #ef2b2b;
	top: 350px;
	width: 100%;
}
/* NEW THREAD*/
.newThread {
	position: absolute;
	width: 80%;
	left: 10%;
}
.newThreadTitle {
	position: absolute;
	left: 0px;
	width: 100%;
	top: 25px;
}
.newThreadMessage {
	position: absolute;
	width: 100%;
	left: 0px;
	top: 70px;
	height: 200px;

}
.newThread .button {
	top: 280px;
}
.newThread .label {
	position: absolute;
}
.newThread .label.title {
	top: 5px;
}
.newThread .label.message {
	top: 50px;
}
/* PREFERENCES*/
.preferences {
	position: absolute;
	width: 100%;
	height: 250px;
}
.preferences .button.update {
	right: 50px;
	bottom: 40px;
}
.preferences .button.file {
	width: 150px;
	left: 50px;
	bottom: 20px;
}
.preferences .label {
	position: absolute;
}
.preferences .label.status {
	left: 210px;
	bottom: 20px;
}
.preferences .label.file {
	left: 50px;
	bottom: 50px;
}
.preferences .label.atmosphere {
	left: 50px;
	top: 10px;
}
.preferences .label.theme {
	left: 50px;
	top: 90px;
}
.colors {
	position: absolute;
	width: 50px;
	height: 50px;
	background-color: white;
}
.colors.atmos {
	top: 35px;
}
.colors.theme {
	top: 115px;
}
/* TABLET */
@media only screen and (max-width: 1100px) {
	main, #backgroundEffect {
		width: 80%;	
	}
	header h1 {
		display: none;
	}
	#headerLogo {
		left: 20px;
	}
	#reaction {
		width: 100%;
		margin-left: 0px;
	}
	.timeStamp {
		display: none;
	}
	.reaction .message {
		min-height: 150px;
		left: 135px;
		width: calc(100% - 130px);
	}
	.reaction .messageEdit {
		min-height: 150px;
		left: 135px;
		width: calc(100% - 135px);
	}
	.reaction .messageEditTitle {
		left: 135px;
		width: calc(100% - 135px);
	}
	.reaction .editTitle {
		left: 135px;
		width: calc(100% - 135px);
	}
	.reaction .delete {
		left: 2px;
		bottom: 27px;
	}
	.reaction .name {
		left: 2px;
		top: 55px;
		width: 150px;
	}
	.react {
		left: 152px;
	}
	.newThread {
		width: 90%;
		left: 5%;
	}
	.thread .threadTitle {
		left: 140px;
	}
	.reaction .timeStampReact {
		font-size: 13px;
		left: 2px;
		top: 70px;
	}
	.button.react {
		left: initial;
		right: 2px;
	}
}
@media only screen and (max-device-width: 1100px) {
	.thread {
		overflow: scroll;
	}
}

@media only screen and (max-width: 600px) {
	.menuBar {
		display: none;
		top: 110px;
		background-color: var(--tCol);
		width: 100%;
		height: <?php if ($logged) {echo '250px';} else {echo '140px';}?>;
	}
	.menuBar .button {
		width: 98%;
		left: 1%;
		height: 40px;
		padding-top: 10px;
	}
	.menuBar .button.one {
		bottom: 2px;
	}
	.menuBar .button.two {
		bottom: 46px;
	}
	.menuBar .button.three {
		bottom: 90px;
	}
	.menuBar .button.four {
		bottom: 134px;
	}
	.stackButton {
		display: block;
		z-index: 10;
	}
	.preferences {
		height: 277px;
		overflow: hidden;
	}
	.preferences .button.file {
		bottom: 50px;
	}
	.preferences .label.status {
		bottom: 27px;
		left: 50px;
		width: 80%;
	}
	.preferences .button.update {
		bottom: 0px;
		left: 50px;
	}
	.preferences .label.file {
		bottom: 78px;
	}
}
</style>