<?php

namespace Ensue\GA4\Constants;

class Metrics
{
    //Event
    public const EVENT_COUNT = 'eventCount';
    public const EVENT_COUNT_PER_USER = 'eventCountPerUser';
    public const EVENT_VALUE = 'eventValue';
    public const EVENTS_PER_SESSION = 'eventsPerSession';

    //Session
    public const SESSIONS = 'sessions';
    public const AVG_SESSION_DURATION = 'averageSessionDuration';
    public const SESSION_PER_USER = 'sessionsPerUser';
    public const BOUNCE_RATE = 'bounceRate';
    public const ENGAGED_SESSIONS = 'engagedSessions';
    public const ENGAGEMENT_RATE = 'engagementRate';

    //User
    public const ACTIVE_USERS = 'activeUsers';
    public const NEW_USERS = 'newUsers';
    public const TOTAL_USERS = 'totalUsers';
    public const USER_ENGAGEMENT_DURATION = 'userEngagementDuration';

    //Page/Screen
    public const SCREEN_PAGE_VIEWS = 'screenPageViews';
    public const SCREEN_PAGE_VIEWS_PER_SESSION = 'screenPageViewsPerSession';

}
