<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 19/07/2017
 * Time: 2:36 PM
 */
$offences = array(
    "Formal Warning"=>array(
        " Breaking any of the posting rules",
        " Breaking any specific thread rules; Some threads will have specific rules that pertain to that thread",
       "   Abusing the report system; this includes reporting one user or a specific post multiple times in a short period of time as well as reporting posts that don’t violate any rules multiple times."
    ),
    "2 Hour Probation"=>array(
        "Breaking any of the posting rules multiple times; this includes double posting, spamming, and posting off topic",
        " Mild Harassment of another user(s)",
    ),
    "24 Hour Probation"=>array(
        "Any repetition of any 2-hour offense(s)",
        " Any targeted harassment, bullying, or hate speech that goes past the mild stage or that continues for more than a short period-of-time",
        "Abuse of any user through the private messaging system(s)",
    ),
    "1 Week Probation"=>array(
        "Any repetition of any lower-tier offense",
        "Undermining of moderator/administrator actions or exploiting flaws in the forum without alerting an administrator; this includes removing moderator edits and not listening to moderators after being warned.",
    ),
    "Permanent Ban"=>array(
      "Advertising another agency",
      "leaking information that was listed as classified on the forum to other agency members or agencies",
      "offense deemed fit by an administrator can result in a permanent ban",
      "The permanent ban is approved by a founder or administrator of the forum",
      "Any illegal actions will constitute an instant permanent ban on the forum.",
    ),
);

echo json_encode($offences);