<?php

namespace App\Http\Controllers\Adm;

use App\Models\Mship\Account;
use App\Models\Statistic;
use App\Models\Mship\Account\Email as AccountEmail;
use Session;
use Response;
use Redirect;
use View;
use Input;
use Cache;

class Dashboard extends \App\Http\Controllers\Adm\AdmController {

    public function getIndex() {
        $statistics = Cache::tags((new \App\Models\Mship\Account())->getTable())->remember("statistics.mship", 60, function() {
            // All Stats
            $statistics = array();
            $statistics['members_total'] = (\App\Models\Mship\Account::count());
            $statistics['members_active'] = (\App\Models\Mship\Account::where("status", "=", 0)->count());
            $statistics['members_division'] = (\App\Models\Mship\Account\State::where("state", "=", \App\Models\Mship\Account\State::STATE_DIVISION)->count());
            $statistics['members_nondivision'] = (\App\Models\Mship\Account\State::where("state", "!=", \App\Models\Mship\Account\State::STATE_DIVISION)->count());
            $statistics['members_pending_update'] = (\App\Models\Mship\Account::where("cert_checked_at", "<=", \Carbon\Carbon::now()
                ->subDay()->toDateTimeString())->where('last_login', '>=', \Carbon\Carbon::now()
                ->subMonths(3)->toDateTimeString())->count());
            $statistics['members_qualifications'] = (\App\Models\Mship\Account\Qualification::count());
            return $statistics;
        });

        $membershipStats = Cache::tags((new Statistic())->getTable())->remember("statistics.membership.graph", 60 * 24, function() {
            $membershipStats = array();
            $membershipStatsKeys = ["members.division.current", "members.division.new", "members.new", "members.current"];
            $date = \Carbon\Carbon::parse("45 days ago");
            while ($date->lt(\Carbon\Carbon::parse("today midnight"))) {
                $counts = array();
                foreach ($membershipStatsKeys as $key) {
                    $counts[$key] = Statistic::getStatistic($date->toDateString(), $key);
                }
                $membershipStats[$date->toDateString()] = $counts;
                $date->addDay();
            }
            return $membershipStats;
        });

        return $this->viewMake("adm.dashboard")
                        ->with("statistics", $statistics)
                        ->with("membershipStats", $membershipStats);
    }

    public function anySearch($searchQuery = null) {
        if ($searchQuery == null) {
            $searchQuery = Input::get("q", null);
        }

        if (strlen($searchQuery) < 2 OR $searchQuery == null) {
            return Redirect::route("adm.dashboard");
        }

        // Direct member?
        if (is_numeric($searchQuery) && Account::find($searchQuery)) {
            return Redirect::route("adm.mship.account.details", [$searchQuery]);
        }

        // Global searches!
        $members = Cache::remember("adm_dashboard_membersearch_{$searchQuery}", 60, function () use ($searchQuery) {
            return Account::where("account_id", "LIKE", "%" . $searchQuery . "%")
                ->orWhere(\DB::raw("CONCAT(`name_first`, ' ', `name_last`)"), "LIKE", "%" . $searchQuery . "%")
                ->limit(25)
                ->get();
        });

        $emails = Cache::remember("adm_dashboard_emailssearch_{$searchQuery}", 60, function () use ($searchQuery) {
            return AccountEmail::withTrashed()
                ->where("email", "LIKE", "%" . $searchQuery . "%")
                ->limit(25)
                ->get();
        });

        $this->setTitle("Global Search Results: " . $searchQuery);
        return $this->viewMake("adm.search")
                        ->with("members", $members)
                        ->with("emails", $emails);
    }

}