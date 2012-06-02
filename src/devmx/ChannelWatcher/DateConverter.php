<?php

/**
 * This file is part of the Teamspeak3 ChannelWatcher.
 * Copyright (C) 2012 drak3 <drak3@live.de>
 * Copyright (C) 2012 Maxe <maxe.nr@live.de>
 * 
 * The Teamspeak3 ChannelWatcher is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * The Teamspeak3 ChannelWatcher is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Teamspeak3 ChannelWatcher.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

namespace devmx\ChannelWatcher;

/**
 *
 * @author drak3
 */
class DateConverter {

    protected static $dateIntervalProperties = array(
        'years' => 'y',
        'months' => 'm',
        'days' => 'd',
        'hours' => 'h',
        'minutes' => 'i',
        'seconds' => 's',
    );

    /**
     * Converts a timespec to a DateInterval
     * @param array $timeSpec possible keys: years, months, weeks, days, hours, minutes, seconds
     * e.g:
     * array(
     *  'weeks' => 1
     *  'days' => 12
     * );          
     */
    public static function convertArrayToInterval(array $timeSpec) {
        $timeSpec = static::normalizeSpec($timeSpec);
        $interval = new \DateInterval('P1Y');
        $interval->y = 0;
        foreach ($timeSpec as $name => $value) {
            $property = static::$dateIntervalProperties[$name];
            $interval->$property = $value;
        }
        return $interval;
    }

    protected static function normalizeSpec(array $timeSpec) {
        $timeSpec = static::lowerNames($timeSpec);
        static::checkKeys($timeSpec);
        $timeSpec = static::lowerWeeks($timeSpec);
        $timeSpec = static::removeNulls($timeSpec);
        return $timeSpec;
    }

    protected static function lowerNames(array $timeSpec) {
        $normalized = array();
        foreach ($timeSpec as $name => $value) {
            $normalized[strtolower($name)] = $value;
        }
        return $normalized;
    }

    protected static function checkKeys(array $timeSpec) {
        foreach ($timeSpec as $name => $value) {
            if ($name !== 'weeks' && !isset(static::$dateIntervalProperties[$name])) {
                throw new \InvalidArgumentException("Unknown time specifier $name");
            }
        }
    }

    protected static function lowerWeeks(array $timeSpec) {
        $days = isset($timeSpec['days']) ? $timeSpec['days'] : 0;
        if (isset($timeSpec['weeks'])) {
            $days += 7 * $timeSpec['weeks'];
        }
        unset($timeSpec['weeks']);
        $timeSpec['days'] = $days;
        return $timeSpec;
    }

    protected static function removeNulls(array $timeSpec) {
        foreach ($timeSpec as $name => $value) {
            if ($value === 0) {
                unset($timeSpec[$name]);
            }
        }
        return $timeSpec;
    }

}

?>
