<?php
use yii\helpers\Url;
?>
<center>
	<table width="100%" height="100%" cellspacing="0" cellpadding="0"
		border="0" bgcolor="#F3F3F3"
		style="background: #f3f3f3; height: 100% !important; margin: 0; padding: 0; width: 100% !important">
		<tbody>
			<tr>
				<td valign="top" align="center" style="border-collapse: collapse">
					<table width="560" cellspacing="0" cellpadding="15" border="0"
						bgcolor="#F3F3F3" style="background: #f3f3f3">
						<tbody>
							<tr>
								<td valign="top" style="border-collapse: collapse">
									<div align="center"
										style="color: #808080; font-family: Arial; font-size: 11px; line-height: 150%; text-align: center">
							<?=Yii::t('app/message','msg natal & happy new year')?>
						</div>

									<div align="center"
										style="color: #808080; font-family: Arial; font-size: 11px; line-height: 150%; text-align: center">
							<?=Yii::t('app/message','msg this email see at not normal')?>
							<a target="_blank"
											style="color: #a30046; font-weight: normal; text-decoration: underline"
											href="http://www.tiket.bdoindonesia.com"><?=Yii::t('app/message','msg see at your browser')?></a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<table width="560" cellspacing="0" cellpadding="0" border="0"
						bgcolor="#FFFFFF"
						style="background: #ffffff; border: 1px solid #cccccc">
						<tbody>
							<tr>
								<td valign="top" align="center"
									style="border-collapse: collapse">

									<table width="100%" cellspacing="0" cellpadding="23" border="0"
										bgcolor="#FFFFFF"
										style="background: #ffffff; border-bottom-width: 0">
										<tbody>
											<tr>
												<td valign="top" align="left"
													style="border-collapse: collapse; text-align: left; vertical-align: top">

													<a target="_blank"
													style="color: #a30046; font-weight: normal; text-decoration: none"
													href="http://newsclick.bukalapak.com/track/click/30012490/www.bukalapak.com?p=eyJzIjoid1VVdXFib0U4UURGOFpLMlBLSkVwTF9HVHpRIiwidiI6MSwicCI6IntcInVcIjozMDAxMjQ5MCxcInZcIjoxLFwidXJsXCI6XCJodHRwczpcXFwvXFxcL3d3dy5idWthbGFwYWsuY29tXCIsXCJpZFwiOlwiMjIwNDE0ZDllZWM0NGNiNGI4ZGQ4MGRlNGIyYTQyZWJcIixcInVybF9pZHNcIjpbXCIxMWI2ODUyZDQzYzBjMTg2NWFlMzViMTEwMThlZjE0MmNiOWM1ZmNiXCJdfSJ9">
														<img
														style="border: 0; min-height: auto; line-height: 100%; max-width: 162px; outline: none; text-decoration: none"
														src="http://tiket.bdoindonesia.com/web/assets/images/logo-mail.png"
														alt="Bukalapak.com - mudah &amp; terpercaya"
														class="CToWUd">
												</a>

												</td>
												<td valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; border: 0; vertical-align: top">
													<div align="right" style="text-align: right">
														<a target="_blank" href="http://www.bdo.co.id"><img
															style="border: 0; display: inline; min-height: auto; line-height: 100%; outline: none; text-decoration: none"
															src="http://tiket.bdoindonesia.com/web/assets/images/bdo.png"
															alt="Pinterest" class="CToWUd"></a>
													</div>

												</td>
											</tr>
										</tbody>
									</table>

								</td>
							</tr>

							<tr>
								<td valign="top" align="center"
									style="border-collapse: collapse">

									<table width="560" cellspacing="0" cellpadding="0" border="0"
										style="color: #808080; font-family: Arial; font-size: 11px; line-height: 150%; text-align: left">
										<tbody>
											<tr>
												<td width="23" style="border-collapse: collapse">&nbsp;</td>
												<td valign="top" style="border-collapse: collapse"
													colspan="7">
													<div>
														<h1 align="left"
															style="border-bottom-color: #e8e8e8; border-bottom-style: solid; border-bottom-width: 1px; color: #a30046; display: block; font-family: Arial; font-size: 24px; font-weight: bold; line-height: 80%; margin: 0 0 10px; text-align: left">
															&nbsp; <br>
															<?=Yii::t('app','open ticket')?>
															<br> &nbsp;
														</h1>
														<br />
														<p
															style="font-family: Arial; font-size: 8pt; font-weight: normal">
															<?=Yii::t('app/message','msg email helpdesk create a new ticket')?>
														</p>
														<br />
													</div>
												</td>
												<td width="23" style="border-collapse: collapse">&nbsp;</td>
											</tr>
											<tr style="padding: 5px">
												<td width="30" style="border-collapse: collapse"></td>
												<td width="100" valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"><?=Yii::t('app','id')?></td>
												<td width="30"
													style="border-collapse: collapse; padding: 5px">:</td>
												<td valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"
													colspan="6">#<?=$data->ticket_id?></td>
											</tr>

											<tr>
												<td width="30" style="border-collapse: collapse"></td>
												<td width="100" valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"><?=Yii::t('app','date')?></td>
												<td width="30"
													style="border-collapse: collapse; padding: 5px">:</td>
												<td valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"
													colspan="6"><?=$data->ticket_date?></td>
											</tr>

											<tr>
												<td width="30" style="border-collapse: collapse"></td>
												<td width="100" valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"><?=Yii::t('app','subject')?></td>
												<td width="30"
													style="border-collapse: collapse; padding: 5px">:</td>
												<td valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"
													colspan="6"><?=$data->ticket_subject?></td>
											</tr>

											<tr>
												<td width="30" style="border-collapse: collapse"></td>
												<td width="100" valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"><?=Yii::t('app','name')?></td>
												<td width="30"
													style="border-collapse: collapse; padding: 5px">:</td>
												<td valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"
													colspan="6"><?=$data->employee_name?></td>
											</tr>

											<tr>
												<td width="30" style="border-collapse: collapse"></td>
												<td width="100" valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"><?=Yii::t('app','type')?></td>
												<td width="30"
													style="border-collapse: collapse; padding: 5px">:</td>
												<td valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"
													colspan="6"><?=$data->ticket_type?></td>
											</tr>

											<tr>
												<td width="30"
													style="border-collapse: collapse; padding: 5px"></td>
												<td width="100" valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"><?=Yii::t('app','description')?></td>
												<td width="30"
													style="border-collapse: collapse; padding: 5px">:</td>
												<td valign="top" bgcolor="#FFFFFF"
													style="background: #ffffff; border-collapse: collapse; padding: 5px"
													colspan="6"><?=$data->ticket_note?></td>
											</tr>

											<tr>
												<td width="23" height="10"
													style="border-collapse: collapse; font-size: 8px; line-height: 100%; padding: 5px">&nbsp;</td>
												<td height="10"
													style="border-collapse: collapse; border-top-color: #e8e8e8; border-top-style: solid; border-top-width: 1px; font-size: 8px; line-height: 100%; padding: 5px"
													colspan="7">&nbsp;</td>
												<td width="23" height="10"
													style="border-collapse: collapse; font-size: 8px; line-height: 100%; padding: 5px">&nbsp;</td>
											</tr>
										</tbody>
									</table>

								</td>
							</tr>

							<tr>
								<td
									style="border-collapse: collapse; font-size: 8px; line-height: 100%">&nbsp;</td>
							</tr>
							<tr>
								<td valign="top" align="center"
									style="border-collapse: collapse"><a target="_blank"
									style="margin: 0; padding: 7px 17px; color: #fff; text-decoration: none; display: inline-block; margin-bottom: 0; vertical-align: middle; line-height: 20px; font-size: 13px; font-weight: 600; text-align: center; white-space: nowrap; border-radius: 2px; background-color: #d15050; background-image: linear-gradient(top, #57b150 0%, #479a40 100%); border: 1px solid #398433"
									href="http://tiket.bdoindonesia.com/<?=Url::to(['ticket/index'])?>"><?=Yii::t('app','see ticket')?></a>
								</td>
							</tr>

							<tr>
								<td valign="top" align="center"
									style="border-collapse: collapse">

									<table width="560" cellspacing="0" cellpadding="23" border="0"
										bgcolor="#FFFFFF"
										style="background: #ffffff; border-top-width: 0">
										<tbody>
											<tr>
												<td valign="top" style="border-collapse: collapse">

													<table width="100%" cellspacing="0" cellpadding="0"
														border="0">
														<tbody>
															<tr>
																<td valign="middle" bgcolor="#FFFFFF"
																	style="background: #ffffff; border-collapse: collapse; border: 0">
																	<div align="center"
																		style="border-top-color: #e8e8e8; border-top-style: solid; border-top-width: 1px; color: #707070; font-family: Arial; font-size: 10px; line-height: 150%; text-align: center">
																		<?=Yii::t('app','copyright bki')?>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>

												</td>
											</tr>
										</tbody>
									</table>

								</td>
							</tr>
						</tbody>
					</table> <br>
				</td>
			</tr>
		</tbody>
	</table>
	<center>