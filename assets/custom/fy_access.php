<?php

function fy_build_date_range_from_label($fyLabel) {
    if (!$fyLabel || strpos($fyLabel, '-') === false) {
        return array(null, null);
    }

    $parts = explode('-', $fyLabel);
    if (sizeof($parts) !== 2) {
        return array(null, null);
    }

    $startYear = (int)trim($parts[0]);
    $endShort = (int)trim($parts[1]);
    $century = (int)floor($startYear / 100) * 100;
    $endYear = $century + $endShort;
    if ($endYear < $startYear) {
        $endYear += 100;
    }

    $start = sprintf('%04d-04-01', $startYear);
    $end = sprintf('%04d-03-31', $endYear);
    return array($start, $end);
}

function fy_get_current_label($db) {
    $sql = "SELECT year FROM year WHERE current = '1' LIMIT 1";
    $query = $db->query($sql);
    if ($query && $query->num_rows > 0) {
        $row = $query->fetch_assoc();
        return $row['year'];
    }
    return '';
}

function fy_set_session_for_user($db, $username, $userlevel) {
    $isSuperAdmin = ($userlevel === 'sadmin_df56fdg');
    $safeUsername = $db->real_escape_string($username);
    $allowedFy = '';

    if (!$isSuperAdmin) {
        $q = $db->query("SELECT allowed_fy FROM users WHERE username = '$safeUsername' LIMIT 1");
        if ($q && $q->num_rows > 0) {
            $r = $q->fetch_assoc();
            $allowedFy = isset($r['allowed_fy']) ? trim($r['allowed_fy']) : '';
        }
        if ($allowedFy === '') {
            return array(false, 'Financial year access is not assigned for this user.');
        }
    } else {
        $allowedFy = fy_get_current_label($db);
    }

    if ($allowedFy === '') {
        return array(false, 'Unable to resolve financial year.');
    }

    list($start, $end) = fy_build_date_range_from_label($allowedFy);
    if ($start === null || $end === null) {
        return array(false, 'Invalid financial year format configured.');
    }

    $_SESSION['allowed_fy'] = $allowedFy;
    $_SESSION['allowed_fy_start'] = $start;
    $_SESSION['allowed_fy_end'] = $end;
    $_SESSION['fy_locked'] = $isSuperAdmin ? '0' : '1';

    if (!isset($_SESSION['start']) || $_SESSION['fy_locked'] === '1') {
        $_SESSION['start'] = $start;
        $_SESSION['end'] = $end;
    }

    return array(true, 'OK');
}

function fy_is_date_allowed($dateYmd) {
    $fyLocked = isset($_SESSION['fy_locked']) ? $_SESSION['fy_locked'] : '0';
    if ($fyLocked !== '1') {
        return true;
    }
    if (!isset($_SESSION['allowed_fy_start']) || !isset($_SESSION['allowed_fy_end'])) {
        return false;
    }
    return ($dateYmd >= $_SESSION['allowed_fy_start'] && $dateYmd <= $_SESSION['allowed_fy_end']);
}

function fy_assert_or_exit_json($dateYmd, $fieldLabel) {
    if (!fy_is_date_allowed($dateYmd)) {
        echo json_encode(array(
            "success" => false,
            "messages" => "Access denied: " . $fieldLabel . " is outside your allowed financial year."
        ));
        exit;
    }
}

