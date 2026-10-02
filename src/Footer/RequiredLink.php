<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Footer;

/**
 * The links Northwestern's Web Style Guide requires in every footer.
 *
 * From the guide's "Required Elements", updated 2024-08-14. The URLs are
 * the canonical ones, with no redirects. Department Templates 4.0 shows
 * the RESOURCES links under "Northwestern Resources" and the LEGAL links
 * in the bottom bar after the copyright.
 *
 * Apps can add their own footer links but cannot remove these. Anything
 * that needs a subset, such as a text-only mail footer, reads it from
 * here so every surface stays in step with one package release.
 */
enum RequiredLink
{
    case BuildingAccess;
    case CampusEmergencyInformation;
    case Careers;
    case ContactNorthwestern;
    case UniversityPolicies;
    case Accessibility;
    case Disclaimer;
    case PrivacyStatement;
    case ReportAConcern;

    /** The links in the "Northwestern Resources" column, in display order. */
    public const array RESOURCES = [
        self::BuildingAccess,
        self::CampusEmergencyInformation,
        self::Careers,
        self::ContactNorthwestern,
        self::UniversityPolicies,
    ];

    /** The links in the bottom bar, in display order. */
    public const array LEGAL = [
        self::Accessibility,
        self::Disclaimer,
        self::PrivacyStatement,
        self::ReportAConcern,
    ];

    public function label(): string
    {
        return match ($this) {
            self::BuildingAccess => 'Building Access',
            self::CampusEmergencyInformation => 'Campus Emergency Information',
            self::Careers => 'Careers',
            self::ContactNorthwestern => 'Contact Northwestern University',
            self::UniversityPolicies => 'University Policies',
            self::Accessibility => 'Accessibility',
            self::Disclaimer => 'Disclaimer',
            self::PrivacyStatement => 'Privacy Statement',
            self::ReportAConcern => 'Report a Concern',
        };
    }

    public function url(): string
    {
        return match ($this) {
            self::BuildingAccess => 'https://www.northwestern.edu/facilities/building-campus-access/building-access/',
            self::CampusEmergencyInformation => 'https://www.northwestern.edu/emergency/',
            self::Careers => 'https://hr.northwestern.edu/careers/',
            self::ContactNorthwestern => 'https://www.northwestern.edu/contact.html',
            self::UniversityPolicies => 'https://policies.northwestern.edu/',
            self::Accessibility => 'https://www.northwestern.edu/accessibility/report/',
            self::Disclaimer => 'https://www.northwestern.edu/disclaimer.html',
            self::PrivacyStatement => 'https://www.northwestern.edu/privacy/',
            self::ReportAConcern => 'https://www.northwestern.edu/report/',
        };
    }
}
