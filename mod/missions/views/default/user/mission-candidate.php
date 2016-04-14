<?php
/*
 * Author: National Research Council Canada
 * Website: http://www.nrc-cnrc.gc.ca/eng/rd/ict/
 *
 * License: Creative Commons Attribution 3.0 Unported License
 * Copyright: Her Majesty the Queen in Right of Canada, 2015
 */

/*
 * User display within the context of the micro missions plugin.
 */
$user = $vars['user'];
$feedback_string = $_SESSION['candidate_search_feedback'][$user->guid];

$mission_guid = $_SESSION['mission_that_invites'];

// Creates a gray background if the user is not opted in to micro missions.
$background_content = '';
if($user->opt_in_missions != 'gcconnex_profile:opt:yes') {
	$background_content = 'style="background-color:#D3D3D3;"';
}

$user_link = elgg_view('output/url', array(
	    'href' => $user->getURL(),
	    'text' => $user->name
));

// Displays search feedback from simple search.
$feedback_content = '';
if($feedback_string != '') {
	$feedback_content = '<h4>' . elgg_echo('missions:user_matched_by') . ':</h4>';
	$count = 1;
    $feedback_array = explode(',', $feedback_string);
    
    foreach($feedback_array as $feedback) {
        if($feedback) {
            $feedback_content .= '<div name="search-feedback-' . $count . '">' . $feedback . '</div>';
        }
        $count++;
    }
}

$options['type'] = 'object';
$options['subtype'] = 'MySkill';
$options['owner_guid'] = $user->guid;
$user_skills = elgg_get_entities($options);

$skill_set = '';
$count = 1;
foreach($user_skills as $skill) {
	$skill_set .= '<span name="user-skill-' . $count . '" style="margin-right:16px;text-decoration:underline;">' . $skill->title . '</span>';
	$count++;
}

// Displays invitation button if the user is opted in to micro missions.
$button_content = '';
if($user->opt_in_missions == 'gcconnex_profile:opt:yes') {
	if($mission_guid != 0) {
		$mission = get_entity($mission_guid);
		if($user->guid != $mission->owner_guid) {
			$button_content = elgg_view('output/url', array(
			        'href' => elgg_get_site_url() . 'action/missions/invite-user?aid=' . $user->guid . '&mid=' . $mission_guid,
			        'text' => elgg_echo('missions:share_mission_with_user'),
					'is_action' => true,
			        'class' => 'elgg-button btn btn-default'
		    ));
		}
	}
	else {
		if($user->guid != elgg_get_logged_in_user_guid()) {
			$button_content = elgg_view('output/url', array(
					'href' => elgg_get_site_url() . 'missions/mission-select-invite/' . $user->guid,
					'text' => elgg_echo('missions:share_a_mission'),
					'class' => 'elgg-button btn btn-default'
			));
		}
	}
}
else {
	$button_content = elgg_echo('missions:not_participating_in_missions');
}
?>

<div class="col-xs-12" <?php echo $background_content; ?>>
	<div class="col-xs-12">
		<div class="col-xs-2">
			<?php echo elgg_view_entity_icon($user, 'medium'); ?>
		</div>
		<div class="col-xs-8">
			<h3 name="user-name" style="margin-top:16px;"><?php echo $user_link; ?></h3>
			<div name="user-job-title"><?php echo $user->job; ?></div>
			<div name="user-location"><?php echo $user->location; ?></div>
			<br>
			<div>
				<?php echo $skill_set; ?>
			</div>
		</div>
		<div class="col-xs-2">
			<?php echo $button_content; ?>
		</div>
	</div>
	<div class="col-xs-12">
		<?php echo $feedback_content; ?>
	</div>
</div>