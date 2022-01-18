<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '462961841188252'); 
fbq('track', 'PageView');
</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=462961841188252&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-141241508-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-141241508-1');
</script>


<script>
	
window.width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
window.height = window.outerHeight;

function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = 'expires=' + d.toUTCString();
	document.cookie = cname + '=' + cvalue + '; ' + expires + ';' + 'path=/';
}

function getCookie(name) {
	var re = new RegExp(name + "=([^;]+)");
	var value = re.exec(document.cookie);
	return (value != null) ? unescape(value[1]) : null;
}

function eraseCookie(name) {
	setCookie(name, '', -1);
}
	
jQuery(document).ready(function($) {
	
	function calcWinnings() {
		
		var gameType = $('#wager-game-input').val(),
			wagerAmount = $('.wager-amount').val().replace(/,/g, ''), 
			wagerAmountVal = wagerAmount.replace(/,/g, '');
		
		if (gameType == 'Team vs Team') {
			
			if (wagerAmount.match(/^\d+\.\d+$/) || wagerAmount.match(/^-{0,1}\d+$/)) {
				
				if (wagerAmount > 0 && wagerAmount < 501) {
					
					var winnings;
					
					if (window.teamvsteamType == 'spread'){
					
						winnings = (Math.round(parseFloat(wagerAmount)*0.9090));		
						
					}
					else if (window.teamvsteamType == 'moneyline'){
				
						if (window.moneylineVal > 0) {
							winnings = (Math.round(parseFloat(wagerAmount)*(window.moneylineVal/100)));
						}
						else {
							winnings = (Math.round(parseFloat(wagerAmount)/(window.moneylineVal/100)))*-1;
						}		
					
					}
					else if (window.teamvsteamType == 'overunder'){
					
						winnings = (Math.round(parseFloat(wagerAmount)*0.9090));
					
					}
					
					$('.total-winnings-msg').html('').addClass('hide');
					$('.total-winnings-val').html((winnings.toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
					$('.total-winnings-proj').addClass('show');
					$('.submit-bet').removeClass('disabled');
					
				}
				else {
					$('.total-winnings-proj').removeClass('show');
					$('.submit-bet').addClass('disabled');
					
					if (wagerAmount > 500) {
						$('.total-winnings-msg').html('Maximum bet is $500').removeClass('hide');
					}
				}
				
			}
			else {
				$('.total-winnings-proj').removeClass('show');
				$('.submit-bet').addClass('disabled');
			}
			
			
		}
		else {
			
			var wagerType = $('.select-wager').val(),
				winnings, denom, wagerOdds = [], wagerData, teamNumber, teamPlayers,
				wagerTeamCount = $('.contest-tab.show').length,
				wagerTeamInvalid = false, teams = [];
			
			//$('.wager-amount').val(wagerAmountVal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));	
			
			$('.contest-tab.show .select-team').each(function(e){
				
				var $this = $(this),
					val = $this.val(),
					teamCount;
				
				if (val == 'Team' || val == 'Type') {
				
					wagerTeamInvalid = true;
					$('.total-winnings-proj').removeClass('show');
					$('.submit-bet').addClass('disabled');
					//return false;
				
				}
									
				if (e == 0) {
					$('.contest-tab .select-team option').prop('disabled', false);
				}
				
				if (gameType == 'Teams') {	
				
					teams[e] = val.split('_');
					
					$('.contest-tab:not(".contest-tab-'+(e+1)+'") .select-team option[value='+val+']').prop('disabled', true);
					
				}
				else if (gameType == 'Mixed') {
				
					if (val != 'Team' && val != 'Type') {
						
						teams[e] = JSON.parse(val);
						teamCount = teams[e]['team_number'];
						$('.contest-tab:not(".contest-tab-'+(e+1)+'") .select-team option.team-'+(teamCount+1)).prop('disabled', true);
						
					}
					
				}
					
			});
						
			if (wagerTeamInvalid == false) {
				
				if (wagerType == 'win') {
					denom = 1;
				}
				else if (wagerType == 'place') {
					denom = 2;
				}
				else if (wagerType == 'show') {
					denom = 3;
				}
				else {
					denom = 1;
				}
				
				if (gameType == 'Teams') {			
													
					if (wagerAmount.match(/^\d+\.\d+$/) || wagerAmount.match(/^-{0,1}\d+$/)) {
					
						if (wagerType == 'win' || wagerType == 'place' || wagerType == 'show') {
							
							winnings = (wagerAmount*teams[0][1])/denom;
							
						}
						else if (wagerType == 'pick-2') {
	
							winnings = (wagerAmount*teams[0][1]*teams[1][1]*2)/denom;
							
						}
						else if (wagerType == 'pick-2-box') {
							
							winnings = (wagerAmount*teams[0][1]*teams[1][1])/denom;
													
						}
						else if (wagerType == 'pick-3') {
	
							winnings = (wagerAmount*teams[0][1]*teams[1][1]*teams[2][1]*3)/denom;
							
						}
						else if (wagerType == 'pick-3-box') {
							
							winnings = (wagerAmount*teams[0][1]*teams[1][1]*teams[2][1])/denom;
													
						}
						else if (wagerType == 'pick-4') {
	
							winnings = (wagerAmount*teams[0][1]*teams[1][1]*teams[2][1]*teams[3][1]*4)/denom;
							
						}
						else if (wagerType == 'pick-4-box') {
							
							winnings = (wagerAmount*teams[0][1]*teams[1][1]*teams[2][1]*teams[3][1])/denom;
													
						}
						else if (wagerType == 'pick-6') {
	
							winnings = (wagerAmount*teams[0][1]*teams[1][1]*teams[2][1]*teams[3][1]*teams[4][1]*teams[5][1])/denom;
							
						}
						
						$('.total-winnings-val').html((winnings.toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
						//console.log($('.total-winnings-val').html());
						$('.total-winnings-proj').addClass('show');
						$('.submit-bet').removeClass('disabled');
						
					}
					else {
						
						$('.total-winnings-proj').removeClass('show');
						$('.submit-bet').addClass('disabled');
						
					}
								
				}
				else if (gameType == 'Mixed') {
																
					if (wagerAmount.match(/^\d+\.\d+$/) || wagerAmount.match(/^-{0,1}\d+$/)) {
					
						if (wagerType == 'win' || wagerType == 'place' || wagerType == 'show') {
							
							winnings = (wagerAmount*teams[0]['odds_to_1'])/denom;
							
						}
						else if (wagerType == 'pick-2') {
	
							winnings = (wagerAmount*teams[0]['odds_to_1']*teams[1]['odds_to_1']*2)/denom;
							
						}
						else if (wagerType == 'pick-2-box') {
							
							winnings = (wagerAmount*teams[0]['odds_to_1']*teams[1]['odds_to_1'])/denom;
													
						}
						else if (wagerType == 'pick-3') {
	
							winnings = (wagerAmount*teams[0]['odds_to_1']*teams[1]['odds_to_1']*teams[2]['odds_to_1']*3)/denom;
							
						}
						else if (wagerType == 'pick-3-box') {
							
							winnings = (wagerAmount*teams[0]['odds_to_1']*teams[1]['odds_to_1']*teams[2]['odds_to_1'])/denom;
													
						}
						else if (wagerType == 'pick-4') {
	
							winnings = (wagerAmount*teams[0]['odds_to_1']*teams[1]['odds_to_1']*teams[2]['odds_to_1']*teams[3]['odds_to_1']*4)/denom;
							
						}
						else if (wagerType == 'pick-4-box') {
							
							winnings = (wagerAmount*teams[0]['odds_to_1']*teams[1]['odds_to_1']*teams[2]['odds_to_1']*teams[3]['odds_to_1'])/denom;												
						}
						else if (wagerType == 'pick-6') {
	
							winnings = (wagerAmount*teams[0]['odds_to_1']*teams[1]['odds_to_1']*teams[2]['odds_to_1']*teams[3]['odds_to_1']*teams[4]['odds_to_1']*teams[5]['odds_to_1'])/denom;	
							
						}
							
						$('.total-winnings-val').html((winnings.toFixed(2)).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
						$('.total-winnings-proj').addClass('show');
						$('.submit-bet').removeClass('disabled');
						
					}
					else {
						$('.total-winnings-proj').removeClass('show');
						$('.submit-bet').addClass('disabled');
					}
					
				}
				
			}
			else {
				
				$('.total-winnings-proj').removeClass('show');
				
			}
			
			
			if (wagerType == 'win' || wagerType == 'place' || wagerType == 'show') {
				
				$('.tab-header').addClass('hide');
			
			}
			else {
			
				$('.tab-header').removeClass('hide');
			
			}
			
		}
				
	}
	
	if (window.width > 320) {
		var loginPos = window.width - $('.nav-login, .nav-balance').offset().left-$('.nav-login, .nav-balance').width()-10;
		$('.login-box').css('right', loginPos+'px');
	}
	
	$('.nav-login').click(function(){
		$('.login-box').toggleClass('show');
	});
	
	$('.site-header .nav-top img').addClass('show');
	
	$('.lobby-tabs a').click(function(e){
		
		e.preventDefault();
		
		$('.no-contests').remove();
		
		var $this = $(this),
			league = $this.data('league');
			
		$('.lobby-tabs a').removeClass('active');
		
		$this.addClass('active');
		
		if (league == 'all') {
		
			$('.lobby-games .contest-game').addClass('show').show();
		
		}
		else {
			
			$('.lobby-games .contest-game').removeClass('show');
			
			var count = $('.lobby-games .contest-game.contest-'+league).length;
			$('.lobby-games .contest-game.contest-'+league).addClass('show');
			
			if (count == 0) {
				
				setTimeout(function(){
					$('.lobby-games').append('<div class="no-contests">There are no upcoming contests to display.</div>');
				}, 250)
				
			}			
			
		}

	});
	
	$('.how-to-play-tabs a').click(function(e){
		
		e.preventDefault();
		
		var $this = $(this),
			league = $this.data('league');
			
		$('.how-to-play-tabs a').removeClass('active');
		
		$this.addClass('active');
		
		$('.how-to-play').removeClass('active');
		$('.how-to-play-'+league).addClass('active');
		
	});
	
	$('select.select-wager').change(function() {
		
		var $this = $(this),
			val = $this.val();
							
		if (val == 'win' || val == 'place' || val == 'show') { 
			
			
			$('.contest-tab-2').removeClass('show');
			$('.contest-tab-3').removeClass('show');
			$('.contest-tab-4').removeClass('show');
			$('.contest-tab-5').removeClass('show');
			$('.contest-tab-6').removeClass('show');
			$('.wager-description').removeClass('show');
			
			if (val == 'win') {
			
				$('.wager-description-win').addClass('show');
			
			}
			if (val == 'place') {
			
				$('.wager-description-place').addClass('show');
			
			}
			if (val == 'show') {
			
				$('.wager-description-show').addClass('show');
			
			}
			
			
		}
		else if (val == 'pick-2' || val == 'pick-2-box') {
			
			
			$('.contest-tab-2').addClass('show');
			$('.contest-tab-3').removeClass('show');
			$('.contest-tab-4').removeClass('show');
			$('.contest-tab-5').removeClass('show');
			$('.contest-tab-6').removeClass('show');
			$('.wager-description').removeClass('show');
			
			if (val == 'pick-2') {
			
				$('.wager-description-pick-2').addClass('show');
			
			}
			if (val == 'pick-2-box') {
			
				$('.wager-description-pick-2-box').addClass('show');
			
			}
			
		}
		else if (val == 'pick-3' || val == 'pick-3-box') {
			
			$('.contest-tab-2').addClass('show');
			$('.contest-tab-3').addClass('show');
			$('.contest-tab-4').removeClass('show');
			$('.contest-tab-5').removeClass('show');
			$('.contest-tab-6').removeClass('show');
			$('.wager-description').removeClass('show');
			
			if (val == 'pick-3') {
			
				$('.wager-description-pick-3').addClass('show');
			
			}
			if (val == 'pick-3-box') {
			
				$('.wager-description-pick-3-box').addClass('show');
			
			}
			
		}
		else if (val == 'pick-4' || val == 'pick-4-box') {
			
			$('.contest-tab-2').addClass('show');
			$('.contest-tab-3').addClass('show');
			$('.contest-tab-4').addClass('show');
			$('.contest-tab-5').removeClass('show');
			$('.contest-tab-6').removeClass('show');
			$('.wager-description').removeClass('show');
			
			if (val == 'pick-4') {
			
				$('.wager-description-pick-4').addClass('show');
			
			}
			if (val == 'pick-4-box') {
			
				$('.wager-description-pick-4-box').addClass('show');
			
			}
			
		}
		else if (val == 'pick-6') {
			
			$('.contest-tab-2').addClass('show');
			$('.contest-tab-3').addClass('show');
			$('.contest-tab-4').addClass('show');
			$('.contest-tab-5').addClass('show');
			$('.contest-tab-6').addClass('show');
			$('.wager-description').removeClass('show');
			$('.wager-description-pick-6').addClass('show');
			
		}
		
		calcWinnings();
		
	});
	
	$('select.select-team').change(function() {
		
		var $this = $(this),
			val = $this.val(),
			index = $this.data('index');
		
		calcWinnings();
		
	});
	
	$('.submit-bet').click(function(e){
		
		var $this = $(this),
			amount = $('.wager-amount').val(),
			type = $('.select-wager').val(),
			format = $this.data('format');
		
		if ($this.hasClass('disabled') == true) {
			e.preventDefault();
			return false;
		}
		else {
			$('.contest-lines-select').find(':input').prop('disabled', false);
			$this.addClass('disabled').attr('value', 'Loading...')
		}
			
		amount = amount.replace(/,/g, '');
			
		if (format == 'team vs team') {
			
			if (amount.match(/^\d+\.\d+$/) || amount.match(/^-{0,1}\d+$/)) {
				
				
				
			}
			else {
				return false;
			}
			
		}
		else if (format == 'mixed' || format == 'teams') {
			
			if (amount.match(/^\d+\.\d+$/) || amount.match(/^-{0,1}\d+$/)) {
				
				if (type == 'win' || type == 'place' || type == 'show' || type == 'pick-2' || type == 'pick-2-box' || type == 'pick-3' || type == 'pick-3-box' || type == 'pick-4' || type == 'pick-4-box' || type == 'pick-6') {
					
					var team1 = $('.contest-tab-1 .select-team').val();
					
					if (type == 'win' || type == 'place' || type == 'show') {
											
						if (team1 != 'Team') {
							
							var teamName = $('.contest-tab-1 option:selected').attr('name');
							
							var betDetails = type.charAt(0).toUpperCase() + type.slice(1).replace(/-/g, " ").replace("box", "Box") + ' - ' + teamName + ' - Bet $' + amount + ' to Win $' + $('.total-winnings-val').html(); 
							var confirm1 = confirm(betDetails);
							
							if (confirm1 == true) {
							    //Proceed
							} else {
								return false;
							}
									
						}
						else {
							return false;
						}
						
					}
					else if (type == 'pick-2' || type == 'pick-2-box') {
						
						var team2 = $('.contest-tab-2 .select-team').val();
						
						if (team1 != 'Team' && team2 != 'Team') {
							var teamName1 = $('.contest-tab-1 option:selected').attr('name'),
								teamName2 = $('.contest-tab-2 option:selected').attr('name');
								
							var betDetails = type.charAt(0).toUpperCase() + type.slice(1).replace(/-/g, " ").replace("box", "Box") + ' - ' + teamName1 + ', ' + teamName2 + ' - Bet $' + amount + ' to Win $' + $('.total-winnings-val').html(); 
							var confirm1 = confirm(betDetails);
							
							if (confirm1 == true) {
							    //Proceed
							} else {
								return false;
							}
									
						}
						else {
							return false;
						}
						
					}
					else if (type == 'pick-3' || type == 'pick-3-box') {
						
						var team2 = $('.contest-tab-2 .select-team').val(),
							team3 = $('.contest-tab-3 .select-team').val();
						
						if (team1 != 'Team' && team2 != 'Team' && team3 != 'Team') {
	
							var teamName1 = $('.contest-tab-1 option:selected').attr('name'),
								teamName2 = $('.contest-tab-2 option:selected').attr('name'),
								teamName3 = $('.contest-tab-3 option:selected').attr('name');
								
							var betDetails = type.charAt(0).toUpperCase() + type.slice(1).replace(/-/g, " ").replace("box", "Box") + ' - ' + teamName1 + ', ' + teamName2 + ', ' + teamName3 + ' - Bet $' + amount + ' to Win $' + $('.total-winnings-val').html(); 
							var confirm1 = confirm(betDetails);
							
							if (confirm1 == true) {
							    //Proceed
							} else {
								return false;
							}
									
						}
						else {
							return false;
						}
						
					}
					else if (type == 'pick-4' || type == 'pick-4-box') {
						
						var team2 = $('.contest-tab-2 .select-team').val(),
							team3 = $('.contest-tab-3 .select-team').val(),
							team4 = $('.contest-tab-4 .select-team').val();
						
						if (team1 != 'Team' && team2 != 'Team' && team3 != 'Team' && team4 != 'Team') {
	
							var teamName1 = $('.contest-tab-1 option:selected').attr('name'),
								teamName2 = $('.contest-tab-2 option:selected').attr('name'),
								teamName3 = $('.contest-tab-3 option:selected').attr('name'),
								teamName4 = $('.contest-tab-4 option:selected').attr('name');
								
							var betDetails = type.charAt(0).toUpperCase() + type.slice(1).replace(/-/g, " ").replace("box", "Box") + ' - ' + teamName1 + ', ' + teamName2 + ', ' + teamName3 + ', ' + teamName4 + ' - Bet $' + amount + ' to Win $' + $('.total-winnings-val').html(); 
							var confirm1 = confirm(betDetails);
							
							if (confirm1 == true) {
							    //Proceed
							} else {
								return false;
							}
									
						}
						else {
							return false;
						}
						
					}
					else if (type == 'pick-6') {
						
						var team2 = $('.contest-tab-2 .select-team').val(),
							team3 = $('.contest-tab-3 .select-team').val(),
							team4 = $('.contest-tab-4 .select-team').val(),
							team5 = $('.contest-tab-5 .select-team').val(),
							team6 = $('.contest-tab-6 .select-team').val();
						
						if (team1 != 'Team' && team2 != 'Team' && team3 != 'Team' && team4 != 'Team' && team5 != 'Team' && team6 != 'Team') {
	
							var teamName1 = $('.contest-tab-1 option:selected').attr('name'),
								teamName2 = $('.contest-tab-2 option:selected').attr('name'),
								teamName3 = $('.contest-tab-3 option:selected').attr('name'),
								teamName4 = $('.contest-tab-4 option:selected').attr('name'),
								teamName5 = $('.contest-tab-5 option:selected').attr('name'),
								teamName6 = $('.contest-tab-6 option:selected').attr('name');
								
							var betDetails = type.charAt(0).toUpperCase() + type.slice(1).replace(/-/g, " ").replace("box", "Box") + ' - ' + teamName1 + ', ' + teamName2 + ', ' + teamName3 + ', ' + teamName4 + ', ' + teamName5 + ', ' + teamName6 + ' - Bet $' + amount + ' to Win $' + $('.total-winnings-val').html(); 
							
							var confirm1 = confirm(betDetails);
							
							if (confirm1 == true) {
							    //Proceed
							} else {
								return false;
							}
									
						}
						else {
							return false;
						}
						
					}
	
				}
				else {
					alert('Please choose a wager type.');
					return false;
				}
		
			}
			else {
				return false;
			}
			
		}

	});
	
	$('.wager-amount').on('input', function(){
		
		calcWinnings();
		
	});
	
	$('.my-contests-tabs a').click(function(e){
	
		e.preventDefault();
		
		var $this = $(this),
			selector = '.' + $this.data('selector');
		
		$('.my-contests').removeClass('show');
		$('.my-contests-tabs a').removeClass('active');
		
		$(selector).addClass('show');
		$this.addClass('active');
	
	});
	
	$('.btn-share').click(function(e){
		
		e.preventDefault();
		$('.at-svc-compact .at-icon-wrapper').click();
		
	});
	
	$('.nav-request-more').click(function(e){
		e.preventDefault();
		var $this = $(this);
		$this.html('Requested!');
		setTimeout(function(){
			$this.fadeOut();
		}, 2000);
	});
	
	$('.contest-team .team-header').hover(function(){
		
		$('.contest-team-wrap').removeClass('expanded');
		
		if (window.width > 767) {
			$('.team-position.expanded').click();
		}
		
		var $this = $(this).offsetParent(),
			selectedFlag = false,
			hoveredTeam = $this.data('name');
				
		$('.contest-tab.show .select-team').each(function(){
			
			var $that = $(this),
				selectedTeam = $that.val();
							
			if (selectedTeam == 'Team') {
				selectedFlag = true;
			}
				
		});
		
		$('.contest-tab.show .option-select-team').each(function(){
			
			var $that = $(this),
				selected = $that.attr('selected'),
				selectedName = $that.attr('name');
			
			if (selected == 'selected' && selectedName == hoveredTeam) {
				selectedFlag = false;	
				$this.addClass('team-added');
			}
			
		});
				
		if (selectedFlag == true) {
			$this.removeClass('team-added');
			$this.addClass('show-overlay');
		}
		
	}, function(){
		
		var $this = $(this).offsetParent();
		$this.removeClass('show-overlay');
		
	});
	
	var notifierCount = 0;
	
	$('.contest-main').on('click', '.contest-team.show-overlay', function(e){
		
		var $this = $(this),
			teamName = $this.data('name');
				
		$('.contest-tab.show .select-team').each(function(){
			
			var $that = $(this),
				selectedTeam = $that.val();
			
			if (selectedTeam == 'Team') {
				
				$this.addClass('team-added');
				$that.find("option[name='"+teamName+"']").attr("selected",true);
				
				console.log($that.find("option[name='"+teamName+"']"))
				
				$('html, body').animate({ scrollTop: $('.contest-tabs-wrap').offset().top-60 }, 500);
				
				$('.make-a-bet-header').append('<span class="added-to-ticket"><strong>'+teamName+'</strong> added to ticket</span>');
				setTimeout(function(){
					$('.added-to-ticket').fadeOut();
				}, 2500)
				
				return false;
				
			}
		
		});
		
	});
	
	if (window.width < 768) {
		
		$('.site-header').addClass('slim');
		
	}
	
	$('.contest-main').on('click', '.team-position', function(e){
		
		var $this = $(this),
			expand = '.' + $this.data('expand'),
			$expand = $(expand),
			$parent = $this.offsetParent(),
			$teamexpand = $('.'+$this.data('teamexpand'));
		
		if ($this.hasClass('points-0.0') == false && $this.hasClass('golfer') != true) {
			
			if ($expand.hasClass('expanded')) {
				$expand.slideUp().removeClass('expanded');
				$this.removeClass('expanded');
			}
			else {
				$expand.slideDown().addClass('expanded');
				$this.addClass('expanded');
				$teamexpand.addClass('expanded');
			}
			
		}
		
	});
	
	$('.contest-main').on('click', '.show-all-wagers', function(e){
		
		e.preventDefault();
		
		if (window.wagerDown != true) {
			$('.wager-list').slideDown();
			window.wagerDown = true;
			$(this).html('Hide <i class="fas fa-caret-up"></i>');
		}
		else {
			$('.wager-list').slideUp();
			window.wagerDown = false;
			$(this).html('Show <i class="fas fa-caret-down"></i>');
		}
		
	});
	
	$('.team-vs-team-bet').click(function(e){
		
		e.preventDefault();
		
		var $this = $(this),
			team = $this.data('name'),
			type = $this.data('select'),
			league = $this.data('league'),
			contestID = $this.data('contest'),
			contestDate = $this.data('date');
		
		if (league == 'nfl') {
			if ($this.hasClass('contest-status-open-for-betting') == false) {
				return false;
			}
		}
		
		$('.select-team').removeClass('show');
		$('.wager-line-type, .overunderval1, .overunderval2, .overunderval3, #contestID, #contestDate').remove();
				
		if (type == 'spread') {
		
			$('.select-team-spread .option-select-team').each(function(){
				
				var $this1 = $(this);
				if (team == $this1.attr('name')) {
					
					$this1.attr('selected','selected');
					
				}
			
			});
			
			$('.select-team-spread').addClass('show').attr('disabled', 'disabled');
			
			$('.submit-bet-form').append('<input class="wager-line-type wager-line-type-'+type+'" name="wager-line-type" value="'+type+'" type="hidden">');
			
			if (league == 'nfl') {
			
				$('.submit-bet-form').append('<input id="contestID" name="wager-contest" value="'+contestID+'" type="hidden"><input id="contestDate" name="wager-contest-date" value="'+contestDate+'" type="hidden">');
			
			}
			
			window.teamvsteamType = 'spread';
		
		}
		else if (type == 'overunder') {
			
			var overundertext = $this.data('overundertext'),
				overunderval1 = $this.data('overunderval1'),
				overunderval2 = $this.data('overunderval2'),
				overunderval3 = $this.data('overunderval3');
			
			$('.select-team-overunder').html('<option class="option-select-team" value="'+overundertext+'" name="'+overundertext+'">'+overundertext+'</option>');		
			$('.select-team-overunder').addClass('show').attr('disabled', 'disabled');
			
			$('.submit-bet-form').append('<input class="wager-line-type wager-line-type-'+type+'" name="wager-line-type" value="'+type+'" type="hidden">');
			$('.submit-bet-form').append('<input class="overunderval1" name="overunderval1" value="'+overunderval1+'" type="hidden">');
			$('.submit-bet-form').append('<input class="overunderval2" name="overunderval2" value="'+overunderval2+'" type="hidden">');
			$('.submit-bet-form').append('<input class="overunderval3" name="overunderval3" value="'+overunderval3+'" type="hidden">');
			
			if (league == 'nfl') {
				
				$('.submit-bet-form').append('<input id="contestID" name="wager-contest" value="'+contestID+'" type="hidden"><input id="contestDate" name="wager-contest-date" value="'+contestDate+'" type="hidden">');
				
			}
			
			window.teamvsteamType = 'overunder';
			window.overunderVal = '';
		
		}
		else if (type == 'moneyline') {
			
			$('.select-team-moneyline .option-select-team').each(function(){
				
				var $this1 = $(this);
				if (team == $this1.attr('name')) {
					
					$this1.attr('selected','selected');
					window.moneylineVal = $this1.data('spread');
					
				}
			
			});
			
			$('.select-team-moneyline').addClass('show').attr('disabled', 'disabled');
			$('.submit-bet-form').append('<input class="wager-line-type wager-line-type-'+type+'" name="wager-line-type" value="'+type+'" type="hidden">');
			
			if (league == 'nfl') {
				
				$('.submit-bet-form').append('<input id="contestID" name="wager-contest" value="'+contestID+'" type="hidden"><input id="contestDate" name="wager-contest-date" value="'+contestDate+'" type="hidden">');
				
			}
			
			window.teamvsteamType = 'moneyline';
			
		}
		
		$('.bet-ticket-sidebar').addClass('show');
		if (window.width > 767) {
			$('.wager-amount').val('').focus();
		}
		calcWinnings();
		
	});
	
	$('.bet-ticket-close').click(function(e){
		e.preventDefault();
		$('.bet-ticket-sidebar').removeClass('show');
		setTimeout(function(){
			$('.wager-amount').val('')
		}, 500)
		
	})
	
	function check_ITM() {
		
		var pick2 = false,
			pick2box = false,
			pick3 = false,
			pick3box = false,
			pick4 = false,
			pick4box = false,
			pick6 = false;
			
		$('.wager-list-item').each(function(){
			
			var $this = $(this),
				type = $this.data('type'),
				betTeams = $this.data('team');
			
			$('.contest-team .team-header').each(function(i){
				
				var $this2 = $(this),
					team1 = $this2.data('team');
													
				if (type == 'win') {
					
		            if (i == 0 && betTeams[0] == team1) {
		                $this.append('<div class="in-the-money">In the money</div>');
		            }
					
				}
				else if (type == 'show') {
					
					if ((i == 0 || i == 1 || i == 2) && betTeams[0] == team1) {
		                $this.append('<div class="in-the-money">In the money</div>');
		            }
		
				}
				else if (type == 'place') {
					
					if ((i == 0 || i == 1) && betTeams[0] == team1) {
		                $this.append('<div class="in-the-money">In the money</div>');
		            }	
				
				}
				else if (type == 'Pick 2') {
					
					if (i == 0) {
						if (betTeams[0] == team1) {
							pick2 = true;
						}
					}
					if (i == 1 && pick2 == true) {
						if (betTeams[1] == team1) {
							pick2 = true;
							
							if (pick2 == true) {
								$this.append('<div class="in-the-money">In the money</div>');
							}
						}
					}
					
				}
				else if (type == 'Pick 2 Box') {
				
					if (i == 0) {
						if (betTeams[0] == team1 || betTeams[1] == team1) {
							pick2box = true;
						}
					}
					if (i == 1 && pick2 == true) {
						if (betTeams[0] == team1 || betTeams[1] == team1) {
							pick2box = true;
							
							if (pick2 == true) {
								$this.append('<div class="in-the-money">In the money</div>');
							}
						}
					}
				
				}
				else if (type == 'Pick 3') {
				
					if (i == 0) {
						if (betTeams[0] == team1) {
							pick3 = true;
						}
					}
					if (i == 1 && pick3 == true) {
						if (betTeams[1] == team1) {
							pick3 = true;
						}
					}
					if (i == 2 && pick3 == true) {
						if (betTeams[2] == team1) {
							pick3 = true;
							
							if (pick3 == true) {
								$this.append('<div class="in-the-money">In the money</div>');
							}
						}
					}
				
				}
				else if (type == 'Pick 3 Box') {
					
					if (i == 0) {
						if (betTeams[0] == team1 || betTeams[1] == team1 || betTeams[2] == team1) {
							pick3box = true;
						}
					}
					if (i == 1 && pick3box == true) {
						if (betTeams[0] == team1 || betTeams[1] == team1 || betTeams[2] == team1) {
							pick3box = true;
						}
					}
					if (i == 2 && pick3box == true) {
						if (betTeams[0] == team1 || betTeams[1] == team1 || betTeams[2] == team1) {
							pick3box = true;
							
							if (pick3box == true) {
								$this.append('<div class="in-the-money">In the money</div>');
							}
						}
					}
					
				}
				else if (type == 'Pick 4') {
				
					if (i == 0) {
						if (betTeams[0] == team1) {
							pick4 = true;
						}
					}
					if (i == 1 && pick4 == true) {
						if (betTeams[1] == team1) {
							pick4 = true;
						}
					}
					if (i == 2 && pick4 == true) {
						if (betTeams[2] == team1) {
							pick4 = true;
						}
					}
					if (i == 3 && pick4 == true) {
						if (betTeams[3] == team1) {
							pick4 = true;
							
							if (pick4 == true) {
								$this.append('<div class="in-the-money">In the money</div>');
							}
						}
					}
				
				}
				else if (type == 'Pick 4 Box') {
					
					if (i == 0) {
						if (betTeams[0] == team1 || betTeams[1] == team1 || betTeams[2] == team1 || betTeams[3] == team1) {
							pick4box = true;
						}
					}
					if (i == 1 && pick4box == true) {
						if (betTeams[0] == team1 || betTeams[1] == team1 || betTeams[2] == team1 || betTeams[3] == team1) {
							pick4box = true;
						}
					}
					if (i == 2 && pick4box == true) {
						if (betTeams[0] == team1 || betTeams[1] == team1 || betTeams[2] == team1 || betTeams[3] == team1) {
							pick4box = true;
						}
					}
					if (i == 3 && pick4box == true) {
						if (betTeams[0] == team1 || betTeams[1] == team1 || betTeams[2] == team1 || betTeams[3] == team1) {
							pick4box = true;
							
							if (pick4box == true) {
								$this.append('<div class="in-the-money">In the money</div>');
							}
						}
					}
					
				}
				else if (type == 'Pick 6') {
					
					if (i == 0) {
						if (betTeams[0] == team1) {
							pick6 = true;
						}
					}
					if (i == 1 && pick6 == true) {
						if (betTeams[1] == team1) {
							pick6 = true;
						}
					}
					if (i == 2 && pick6 == true) {
						if (betTeams[2] == team1) {
							pick6 = true;
						}
					}
					if (i == 3 && pick6 == true) {
						if (betTeams[3] == team1) {
							pick6 = true;
						}
					}
					if (i == 4 && pick6 == true) {
						if (betTeams[4] == team1) {
							pick6 = true;
						}
					}
					if (i == 5 && pick6 == true) {
						if (betTeams[5] == team1) {
							pick6 = true;
							
							if (pick6 == true) {
								$this.append('<div class="in-the-money">In the money</div>');
							}
						}
					}
					
				}
		
			});
		
		});
	
	}
	
	$('.contest-main').on('click', '.refresh.no-animate', function(e){
		
		e.preventDefault();
		
		var $this = $(this),
			league = $('.refresh-live').data('league');
		
		$('.refresh.no-animate').hide();
		$('.refresh.animate').show();
		
		$.get( "/refresh-"+league+"/", function() { 
		}).done(function() {
			$.get( document.URL, function() { 
			}).done(function(data) {
				$('.contest-main').html($(data).find('.contest-main').html());
				$('.refresh.no-animate').show();
				$('.refresh.animate').hide();
				
				setTimeout(function(){
					check_ITM();
					//$('a.show-all-wagers').click();
				}, 1000);
	  		})
  		})
				
	});
	
	<?php 
	global $post; 
	
	if (is_single() && $post->post_type == 'contest') { 
		
		$contest_status = get_field('contest_status', $post->ID);
		
		if ($contest_status == 'In Progress') { ?>
			
			setTimeout(function(){
				
				setInterval(function(){
				
					$('.refresh.no-animate').click();
					console.log('Scores updated');
					check_ITM();
		  		
		  		}, 300000); // refresh every 5 minutes
				
			}, 60000)
			
			check_ITM();
			
		<?php
			
		} 
		
		?>
		
		/*
		setTimeout(function(){
			$('a.show-all-wagers').click();
		}, 250)
		*/
		
		<?php
	} 
	
	?>
	
	$(window).scroll(function(){
		
		if (window.width > 767) {
		
			var st = $(window).scrollTop();
			
			if (st > 50 && window.navScrolled != true) {
				$('.site-header').addClass('slim');
				window.navScrolled = true;
				window.navScrolled2 = false;

				if ($('.login-box').hasClass('show')) {
					$('.nav-login').click();
				}
				
			}
			
			if (st < 50 && window.navScrolled2 != true) {
				$('.site-header').removeClass('slim');
				window.navScrolled = false;
				window.navScrolled2 = true;
				
				<?php if (is_home() || is_front_page()) { ?>
					if ($('.login-box').hasClass('show') == false) {
						$('.nav-login').click();
					}
				<?php } ?>
				
			}
		
		}
		
	});	
	
	if (window.width > 767) {
		
		<?php if (is_home() || is_front_page()) { ?>
		
			setTimeout(function(){
				
				$('.nav-login').click();
				
			}, 500);
		
		<?php } ?>
		
	}
	else {
		
		$('.contest-sidebar-left').detach().appendTo('.contest-wrap').show();
		
	}
	
	setTimeout(function(){
	
		$('.lobby-games .contest-game, .contest-tabs-wrap, .home-section-1 h1').addClass('show');
			
	}, 500);
	
	$('.section-header-menu').click(function(){
		
		$('.section-header-submenu').toggleClass('show');
		
	});
	
	$('.section-header-submenu').click(function(){
		alert('coming soon')
	});
	
	if (window.width > 768) {
		
		setTimeout(function(){
			var teamHeight = $('.contest-team').height();
			$('.contest-team').css('height',teamHeight+'px');
		}, 1500);
		
	}
	
	$('.nav-menu-right-dots').click(function(e){
		
		e.preventDefault();
		
		window.width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
		if (window.width < 767) {
			
		}
		
	})
	
	
	<?php if (is_user_logged_in() == false) { ?>
	
		$('.nsl-container-buttons a').each(function(){
			var $this = $(this),
				provider = $this.data('provider');
			
			if (provider == 'facebook' || provider == 'google') {
				$this.attr('data-redirect', '<?php echo site_url( '/lobby/ '); ?>');
			} 
			
		});
		
		$('.login-box .login-submit').after('<div style="padding:0;text-align: center;font-weight: bold;font-size: 16px;">- OR -</div>');
			
	<?php } ?>
	
	if (window.width > 767) {
	
		$('.contest-sidebar-left').hcSticky();
	
	}
	
/*!
 * parallax.js v1.5.0 (http://pixelcog.github.io/parallax.js/)
 * @copyright 2016 PixelCog, Inc.
 * @license MIT (https://github.com/pixelcog/parallax.js/blob/master/LICENSE)
 */
!function(t,i,e,s){function o(i,e){var h=this;"object"==typeof e&&(delete e.refresh,delete e.render,t.extend(this,e)),this.$element=t(i),!this.imageSrc&&this.$element.is("img")&&(this.imageSrc=this.$element.attr("src"));var r=(this.position+"").toLowerCase().match(/\S+/g)||[];if(r.length<1&&r.push("center"),1==r.length&&r.push(r[0]),"top"!=r[0]&&"bottom"!=r[0]&&"left"!=r[1]&&"right"!=r[1]||(r=[r[1],r[0]]),this.positionX!==s&&(r[0]=this.positionX.toLowerCase()),this.positionY!==s&&(r[1]=this.positionY.toLowerCase()),h.positionX=r[0],h.positionY=r[1],"left"!=this.positionX&&"right"!=this.positionX&&(isNaN(parseInt(this.positionX))?this.positionX="center":this.positionX=parseInt(this.positionX)),"top"!=this.positionY&&"bottom"!=this.positionY&&(isNaN(parseInt(this.positionY))?this.positionY="center":this.positionY=parseInt(this.positionY)),this.position=this.positionX+(isNaN(this.positionX)?"":"px")+" "+this.positionY+(isNaN(this.positionY)?"":"px"),navigator.userAgent.match(/(iPod|iPhone|iPad)/))return this.imageSrc&&this.iosFix&&!this.$element.is("img")&&this.$element.css({backgroundImage:'url("'+this.imageSrc+'")',backgroundSize:"cover",backgroundPosition:this.position}),this;if(navigator.userAgent.match(/(Android)/))return this.imageSrc&&this.androidFix&&!this.$element.is("img")&&this.$element.css({backgroundImage:'url("'+this.imageSrc+'")',backgroundSize:"cover",backgroundPosition:this.position}),this;this.$mirror=t("<div />").prependTo(this.mirrorContainer);var a=this.$element.find(">.parallax-slider"),n=!1;0==a.length?this.$slider=t("<img />").prependTo(this.$mirror):(this.$slider=a.prependTo(this.$mirror),n=!0),this.$mirror.addClass("parallax-mirror").css({visibility:"hidden",zIndex:this.zIndex,position:"fixed",top:0,left:0,overflow:"hidden"}),this.$slider.addClass("parallax-slider").one("load",function(){h.naturalHeight&&h.naturalWidth||(h.naturalHeight=this.naturalHeight||this.height||1,h.naturalWidth=this.naturalWidth||this.width||1),h.aspectRatio=h.naturalWidth/h.naturalHeight,o.isSetup||o.setup(),o.sliders.push(h),o.isFresh=!1,o.requestRender()}),n||(this.$slider[0].src=this.imageSrc),(this.naturalHeight&&this.naturalWidth||this.$slider[0].complete||a.length>0)&&this.$slider.trigger("load")}!function(){for(var t=0,e=["ms","moz","webkit","o"],s=0;s<e.length&&!i.requestAnimationFrame;++s)i.requestAnimationFrame=i[e[s]+"RequestAnimationFrame"],i.cancelAnimationFrame=i[e[s]+"CancelAnimationFrame"]||i[e[s]+"CancelRequestAnimationFrame"];i.requestAnimationFrame||(i.requestAnimationFrame=function(e){var s=(new Date).getTime(),o=Math.max(0,16-(s-t)),h=i.setTimeout(function(){e(s+o)},o);return t=s+o,h}),i.cancelAnimationFrame||(i.cancelAnimationFrame=function(t){clearTimeout(t)})}(),t.extend(o.prototype,{speed:.2,bleed:0,zIndex:-100,iosFix:!0,androidFix:!0,position:"center",overScrollFix:!1,mirrorContainer:"body",refresh:function(){this.boxWidth=this.$element.outerWidth(),this.boxHeight=this.$element.outerHeight()+2*this.bleed,this.boxOffsetTop=this.$element.offset().top-this.bleed,this.boxOffsetLeft=this.$element.offset().left,this.boxOffsetBottom=this.boxOffsetTop+this.boxHeight;var t,i=o.winHeight,e=o.docHeight,s=Math.min(this.boxOffsetTop,e-i),h=Math.max(this.boxOffsetTop+this.boxHeight-i,0),r=this.boxHeight+(s-h)*(1-this.speed)|0,a=(this.boxOffsetTop-s)*(1-this.speed)|0;r*this.aspectRatio>=this.boxWidth?(this.imageWidth=r*this.aspectRatio|0,this.imageHeight=r,this.offsetBaseTop=a,t=this.imageWidth-this.boxWidth,"left"==this.positionX?this.offsetLeft=0:"right"==this.positionX?this.offsetLeft=-t:isNaN(this.positionX)?this.offsetLeft=-t/2|0:this.offsetLeft=Math.max(this.positionX,-t)):(this.imageWidth=this.boxWidth,this.imageHeight=this.boxWidth/this.aspectRatio|0,this.offsetLeft=0,t=this.imageHeight-r,"top"==this.positionY?this.offsetBaseTop=a:"bottom"==this.positionY?this.offsetBaseTop=a-t:isNaN(this.positionY)?this.offsetBaseTop=a-t/2|0:this.offsetBaseTop=a+Math.max(this.positionY,-t))},render:function(){var t=o.scrollTop,i=o.scrollLeft,e=this.overScrollFix?o.overScroll:0,s=t+o.winHeight;this.boxOffsetBottom>t&&this.boxOffsetTop<=s?(this.visibility="visible",this.mirrorTop=this.boxOffsetTop-t,this.mirrorLeft=this.boxOffsetLeft-i,this.offsetTop=this.offsetBaseTop-this.mirrorTop*(1-this.speed)):this.visibility="hidden",this.$mirror.css({transform:"translate3d("+this.mirrorLeft+"px, "+(this.mirrorTop-e)+"px, 0px)",visibility:this.visibility,height:this.boxHeight,width:this.boxWidth}),this.$slider.css({transform:"translate3d("+this.offsetLeft+"px, "+this.offsetTop+"px, 0px)",position:"absolute",height:this.imageHeight,width:this.imageWidth,maxWidth:"none"})}}),t.extend(o,{scrollTop:0,scrollLeft:0,winHeight:0,winWidth:0,docHeight:1<<30,docWidth:1<<30,sliders:[],isReady:!1,isFresh:!1,isBusy:!1,setup:function(){function s(){if(p==i.pageYOffset)return i.requestAnimationFrame(s),!1;p=i.pageYOffset,h.render(),i.requestAnimationFrame(s)}if(!this.isReady){var h=this,r=t(e),a=t(i),n=function(){o.winHeight=a.height(),o.winWidth=a.width(),o.docHeight=r.height(),o.docWidth=r.width()},l=function(){var t=a.scrollTop(),i=o.docHeight-o.winHeight,e=o.docWidth-o.winWidth;o.scrollTop=Math.max(0,Math.min(i,t)),o.scrollLeft=Math.max(0,Math.min(e,a.scrollLeft())),o.overScroll=Math.max(t-i,Math.min(t,0))};a.on("resize.px.parallax load.px.parallax",function(){n(),h.refresh(),o.isFresh=!1,o.requestRender()}).on("scroll.px.parallax load.px.parallax",function(){l(),o.requestRender()}),n(),l(),this.isReady=!0;var p=-1;s()}},configure:function(i){"object"==typeof i&&(delete i.refresh,delete i.render,t.extend(this.prototype,i))},refresh:function(){t.each(this.sliders,function(){this.refresh()}),this.isFresh=!0},render:function(){this.isFresh||this.refresh(),t.each(this.sliders,function(){this.render()})},requestRender:function(){var t=this;t.render(),t.isBusy=!1},destroy:function(e){var s,h=t(e).data("px.parallax");for(h.$mirror.remove(),s=0;s<this.sliders.length;s+=1)this.sliders[s]==h&&this.sliders.splice(s,1);t(e).data("px.parallax",!1),0===this.sliders.length&&(t(i).off("scroll.px.parallax resize.px.parallax load.px.parallax"),this.isReady=!1,o.isSetup=!1)}});var h=t.fn.parallax;t.fn.parallax=function(s){return this.each(function(){var h=t(this),r="object"==typeof s&&s;this==i||this==e||h.is("body")?o.configure(r):h.data("px.parallax")?"object"==typeof s&&t.extend(h.data("px.parallax"),r):(r=t.extend({},h.data(),r),h.data("px.parallax",new o(this,r))),"string"==typeof s&&("destroy"==s?o.destroy(this):o[s]())})},t.fn.parallax.Constructor=o,t.fn.parallax.noConflict=function(){return t.fn.parallax=h,this},t(function(){t('[data-parallax="scroll"]').parallax()})}(jQuery,window,document);

});
</script>