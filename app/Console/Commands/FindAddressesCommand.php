<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Emergency;
use App\Message;
use Illuminate\Support\Str;

class FindAddressesCommand extends Command
{
    private $suffixes = [
        'ALLEE' => 'ALY',
        'ALLEY' => 'ALY',
        'ALLY' => 'ALY',
        'ANEX' => 'ANX',
        'ANNEX' => 'ANX',
        'ANNX' => 'ANX',
        'ARCADE' => 'ARC',
        'AV' => 'AVE',
        'AVEN' => 'AVE',
        'AVENU' => 'AVE',
        'AVENUE' => 'AVE',
        'AVN' => 'AVE',
        'AVNUE' => 'AVE',
        'BAYOO' => 'BYU',
        'BAYOU' => 'BYU',
        'BEACH' => 'BCH',
        'BEND' => 'BND',
        'BLUF' => 'BLF',
        'BLUFF' => 'BLF',
        'BLUFFS' => 'BLFS',
        'BOT' => 'BTM',
        'BOTTM' => 'BTM',
        'BOTTOM' => 'BTM',
        'BOUL' => 'BLVD',
        'BOULEVARD' => 'BLVD',
        'BOULV' => 'BLVD',
        'BRANCH' => 'BR',
        'BRDGE' => 'BRG',
        'BRIDGE' => 'BRG',
        'BRNCH' => 'BR',
        'BROOK' => 'BRK',
        'BROOKS' => 'BRKS',
        'BURG' => 'BG',
        'BURGS' => 'BGS',
        'BYPA' => 'BYP',
        'BYPAS' => 'BYP',
        'BYPASS' => 'BYP',
        'BYPS' => 'BYP',
        'CAMP' => 'CP',
        'CANYN' => 'CYN',
        'CANYON' => 'CYN',
        'CAPE' => 'CPE',
        'CAUSEWAY' => 'CSWY',
        'CAUSWAY' => 'CSWY',
        'CEN' => 'CTR',
        'CENT' => 'CTR',
        'CENTER' => 'CTR',
        'CENTERS' => 'CTRS',
        'CENTR' => 'CTR',
        'CENTRE' => 'CTR',
        'CIRC' => 'CIR',
        'CIRCL' => 'CIR',
        'CIRCLE' => 'CIR',
        'CIRCLES' => 'CIRS',
        'CK' => 'CRK',
        'CLIFF' => 'CLF',
        'CLIFFS' => 'CLFS',
        'CLUB' => 'CLB',
        'CMP' => 'CP',
        'CNTER' => 'CTR',
        'CNTR' => 'CTR',
        'CNYN' => 'CYN',
        'COMMON' => 'CMN',
        'CORNER' => 'COR',
        'CORNERS' => 'CORS',
        'COURSE' => 'CRSE',
        'COURT' => 'CT',
        'COURTS' => 'CTS',
        'COVE' => 'CV',
        'COVES' => 'CVS',
        'CR' => 'CRK',
        'CRCL' => 'CIR',
        'CRCLE' => 'CIR',
        'CRECENT' => 'CRES',
        'CREEK' => 'CRK',
        'CRESCENT' => 'CRES',
        'CRESENT' => 'CRES',
        'CREST' => 'CRST',
        'CROSSING' => 'XING',
        'CROSSROAD' => 'XRD',
        'CRSCNT' => 'CRES',
        'CRSENT' => 'CRES',
        'CRSNT' => 'CRES',
        'CRSSING' => 'XING',
        'CRSSNG' => 'XING',
        'CRT' => 'CT',
        'CURVE' => 'CURV',
        'DALE' => 'DL',
        'DAM' => 'DM',
        'DIV' => 'DV',
        'DIVIDE' => 'DV',
        'DRIV' => 'DR',
        'DRIVE' => 'DR',
        'DRIVES' => 'DRS',
        'DRV' => 'DR',
        'DVD' => 'DV',
        'ESTATE' => 'EST',
        'ESTATES' => 'ESTS',
        'EXP' => 'EXPY',
        'EXPR' => 'EXPY',
        'EXPRESS' => 'EXPY',
        'EXPRESSWAY' => 'EXPY',
        'EXPW' => 'EXPY',
        'EXTENSION' => 'EXT',
        'EXTENSIONS' => 'EXTS',
        'EXTN' => 'EXT',
        'EXTNSN' => 'EXT',
        'FALLS' => 'FLS',
        'FERRY' => 'FRY',
        'FIELD' => 'FLD',
        'FIELDS' => 'FLDS',
        'FLAT' => 'FLT',
        'FLATS' => 'FLTS',
        'FORD' => 'FRD',
        'FORDS' => 'FRDS',
        'FOREST' => 'FRST',
        'FORESTS' => 'FRST',
        'FORG' => 'FRG',
        'FORGE' => 'FRG',
        'FORGES' => 'FRGS',
        'FORK' => 'FRK',
        'FORKS' => 'FRKS',
        'FORT' => 'FT',
        'FREEWAY' => 'FWY',
        'FREEWY' => 'FWY',
        'FRRY' => 'FRY',
        'FRT' => 'FT',
        'FRWAY' => 'FWY',
        'FRWY' => 'FWY',
        'GARDEN' => 'GDN',
        'GARDENS' => 'GDNS',
        'GARDN' => 'GDN',
        'GATEWAY' => 'GTWY',
        'GATEWY' => 'GTWY',
        'GATWAY' => 'GTWY',
        'GLEN' => 'GLN',
        'GLENS' => 'GLNS',
        'GRDEN' => 'GDN',
        'GRDN' => 'GDN',
        'GRDNS' => 'GDNS',
        'GREEN' => 'GRN',
        'GREENS' => 'GRNS',
        'GROV' => 'GRV',
        'GROVE' => 'GRV',
        'GROVES' => 'GRVS',
        'GTWAY' => 'GTWY',
        'HARB' => 'HBR',
        'HARBOR' => 'HBR',
        'HARBORS' => 'HBRS',
        'HARBR' => 'HBR',
        'HAVEN' => 'HVN',
        'HAVN' => 'HVN',
        'HEIGHT' => 'HTS',
        'HEIGHTS' => 'HTS',
        'HGTS' => 'HTS',
        'HIGHWAY' => 'HWY',
        'HIGHWY' => 'HWY',
        'HILL' => 'HL',
        'HILLS' => 'HLS',
        'HIWAY' => 'HWY',
        'HIWY' => 'HWY',
        'HLLW' => 'HOLW',
        'HOLLOW' => 'HOLW',
        'HOLLOWS' => 'HOLW',
        'HOLWS' => 'HOLW',
        'HRBOR' => 'HBR',
        'HT' => 'HTS',
        'HWAY' => 'HWY',
        'INLET' => 'INLT',
        'ISLAND' => 'IS',
        'ISLANDS' => 'ISS',
        'ISLES' => 'ISLE',
        'ISLND' => 'IS',
        'ISLNDS' => 'ISS',
        'JCTION' => 'JCT',
        'JCTN' => 'JCT',
        'JCTNS' => 'JCTS',
        'JUNCTION' => 'JCT',
        'JUNCTIONS' => 'JCTS',
        'JUNCTN' => 'JCT',
        'JUNCTON' => 'JCT',
        'KEY' => 'KY',
        'KEYS' => 'KYS',
        'KNOL' => 'KNL',
        'KNOLL' => 'KNL',
        'KNOLLS' => 'KNLS',
        'LA' => 'LN',
        'LAKE' => 'LK',
        'LAKES' => 'LKS',
        'LANDING' => 'LNDG',
        'LANE' => 'LN',
        'LANES' => 'LN',
        'LDGE' => 'LDG',
        'LIGHT' => 'LGT',
        'LIGHTS' => 'LGTS',
        'LNDNG' => 'LNDG',
        'LOAF' => 'LF',
        'LOCK' => 'LCK',
        'LOCKS' => 'LCKS',
        'LODG' => 'LDG',
        'LODGE' => 'LDG',
        'LOOPS' => 'LOOP',
        'MANOR' => 'MNR',
        'MANORS' => 'MNRS',
        'MEADOW' => 'MDW',
        'MEADOWS' => 'MDWS',
        'MEDOWS' => 'MDWS',
        'MILL' => 'ML',
        'MILLS' => 'MLS',
        'MISSION' => 'MSN',
        'MISSN' => 'MSN',
        'MNT' => 'MT',
        'MNTAIN' => 'MTN',
        'MNTN' => 'MTN',
        'MNTNS' => 'MTNS',
        'MOTORWAY' => 'MTWY',
        'MOUNT' => 'MT',
        'MOUNTAIN' => 'MTN',
        'MOUNTAINS' => 'MTNS',
        'MOUNTIN' => 'MTN',
        'MSSN' => 'MSN',
        'MTIN' => 'MTN',
        'NECK' => 'NCK',
        'ORCHARD' => 'ORCH',
        'ORCHRD' => 'ORCH',
        'OVERPASS' => 'OPAS',
        'OVL' => 'OVAL',
        'PARKS' => 'PARK',
        'PARKWAY' => 'PKWY',
        'PARKWAYS' => 'PKWY',
        'PARKWY' => 'PKWY',
        'PASSAGE' => 'PSGE',
        'PATHS' => 'PATH',
        'PIKES' => 'PIKE',
        'PINE' => 'PNE',
        'PINES' => 'PNES',
        'PK' => 'PARK',
        'PKWAY' => 'PKWY',
        'PKWYS' => 'PKWY',
        'PKY' => 'PKWY',
        'PLACE' => 'PL',
        'PLAIN' => 'PLN',
        'PLAINES' => 'PLNS',
        'PLAINS' => 'PLNS',
        'PLAZA' => 'PLZ',
        'PLZA' => 'PLZ',
        'POINT' => 'PT',
        'POINTS' => 'PTS',
        'PORT' => 'PRT',
        'PORTS' => 'PRTS',
        'PRAIRIE' => 'PR',
        'PRARIE' => 'PR',
        'PRK' => 'PARK',
        'PRR' => 'PR',
        'RAD' => 'RADL',
        'RADIAL' => 'RADL',
        'RADIEL' => 'RADL',
        'RANCH' => 'RNCH',
        'RANCHES' => 'RNCH',
        'RAPID' => 'RPD',
        'RAPIDS' => 'RPDS',
        'RDGE' => 'RDG',
        'REST' => 'RST',
        'RIDGE' => 'RDG',
        'RIDGES' => 'RDGS',
        'RIVER' => 'RIV',
        'RIVR' => 'RIV',
        'RNCHS' => 'RNCH',
        'ROAD' => 'RD',
        'ROADS' => 'RDS',
        'ROUTE' => 'RTE',
        'RVR' => 'RIV',
        'SHOAL' => 'SHL',
        'SHOALS' => 'SHLS',
        'SHOAR' => 'SHR',
        'SHOARS' => 'SHRS',
        'SHORE' => 'SHR',
        'SHORES' => 'SHRS',
        'SKYWAY' => 'SKWY',
        'SPNG' => 'SPG',
        'SPNGS' => 'SPGS',
        'SPRING' => 'SPG',
        'SPRINGS' => 'SPGS',
        'SPRNG' => 'SPG',
        'SPRNGS' => 'SPGS',
        'SPURS' => 'SPUR',
        'SQR' => 'SQ',
        'SQRE' => 'SQ',
        'SQRS' => 'SQS',
        'SQU' => 'SQ',
        'SQUARE' => 'SQ',
        'SQUARES' => 'SQS',
        'STATION' => 'STA',
        'STATN' => 'STA',
        'STN' => 'STA',
        'STR' => 'ST',
        'STRAV' => 'STRA',
        'STRAVE' => 'STRA',
        'STRAVEN' => 'STRA',
        'STRAVENUE' => 'STRA',
        'STRAVN' => 'STRA',
        'STREAM' => 'STRM',
        'STREET' => 'ST',
        'STREETS' => 'STS',
        'STREME' => 'STRM',
        'STRT' => 'ST',
        'STRVN' => 'STRA',
        'STRVNUE' => 'STRA',
        'SUMIT' => 'SMT',
        'SUMITT' => 'SMT',
        'SUMMIT' => 'SMT',
        'TERR' => 'TER',
        'TERRACE' => 'TER',
        'THROUGHWAY' => 'TRWY',
        'TPK' => 'TPKE',
        'TR' => 'TRL',
        'TRACE' => 'TRCE',
        'TRACES' => 'TRCE',
        'TRACK' => 'TRAK',
        'TRACKS' => 'TRAK',
        'TRAFFICWAY' => 'TRFY',
        'TRAIL' => 'TRL',
        'TRAILS' => 'TRL',
        'TRK' => 'TRAK',
        'TRKS' => 'TRAK',
        'TRLS' => 'TRL',
        'TRNPK' => 'TPKE',
        'TRPK' => 'TPKE',
        'TUNEL' => 'TUNL',
        'TUNLS' => 'TUNL',
        'TUNNEL' => 'TUNL',
        'TUNNELS' => 'TUNL',
        'TUNNL' => 'TUNL',
        'TURNPIKE' => 'TPKE',
        'TURNPK' => 'TPKE',
        'UNDERPASS' => 'UPAS',
        'UNION' => 'UN',
        'UNIONS' => 'UNS',
        'VALLEY' => 'VLY',
        'VALLEYS' => 'VLYS',
        'VALLY' => 'VLY',
        'VDCT' => 'VIA',
        'VIADCT' => 'VIA',
        'VIADUCT' => 'VIA',
        'VIEW' => 'VW',
        'VIEWS' => 'VWS',
        'VILL' => 'VLG',
        'VILLAG' => 'VLG',
        'VILLAGE' => 'VLG',
        'VILLAGES' => 'VLGS',
        'VILLE' => 'VL',
        'VILLG' => 'VLG',
        'VILLIAGE' => 'VLG',
        'VIST' => 'VIS',
        'VISTA' => 'VIS',
        'VLLY' => 'VLY',
        'VST' => 'VIS',
        'VSTA' => 'VIS',
        'WALKS' => 'WALK',
        'WELL' => 'WL',
        'WELLS' => 'WLS',
        'WY' => 'WAY'
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds emergencies from tweets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Very crude and rudimentary right now
        $allSuffixes = array_combine(array_keys($this->suffixes), array_values($this->suffixes));

        $messages = Message::where('is_processed', false)
        ->doesntHave('emergencies')
        ->get()
        ->filter(function ($message) use ($allSuffixes) {
            $text = $message->message_text;

            // Do we have at least 3 digits? (Could be a house number or zip code)
            if (preg_match('/([0-9]){3,}/i', $this->textWithoutHandlesAndHashtags($text))) {
                return true;
            }

            return false;
        })->each(function ($message) use ($allSuffixes) {
            $message->is_processed = true;
            $message->save();

            // Find address to geocode
            $cleanText = $this->textWithoutHandlesAndHashtags($message->message_text);

            // Start with the first number to some kind of suffix
            preg_match_all('/([0-9]){3,}(.*)?('. implode('|', $allSuffixes) .')/i', $cleanText, $matches);

            foreach ($matches as $match) {
                if (!isset($match[0])) {
                    continue;
                }

                $query = $match[0] . ' ' . $this->determineCityState($cleanText);

                $result = $this->geocode($query);
                $firstResult = $result->results ? $result->results[0] : null;

                if ($firstResult && $firstResult->accuracy > 0.8 && $firstResult->address_components->state === 'TX' && isset($firstResult->address_components->number)) {
                    $emergency = new Emergency();
                    $emergency->message_id = $message->id;
                    $emergency->lat = $firstResult->location->lat;
                    $emergency->lng = $firstResult->location->lng;
                    $emergency->accuracy_score = $firstResult->accuracy;
                    $emergency->accuracy_type = $firstResult->accuracy_type;
                    $emergency->street = $firstResult->address_components->number . ' ' . $firstResult->address_components->formatted_street;
                    $emergency->city = $firstResult->address_components->city;
                    $emergency->zip = $firstResult->address_components->zip;
                    $emergency->state = $firstResult->address_components->state;
                    $emergency->save();
                    break;
                }
            }

            return $message->message_text;
        });
    }

    private function textWithoutHandlesAndHashtags($text) {
        $text = preg_replace('/\S*@(?:\[[^\]]+\]|\S+)/', '', $text);
        $text = preg_replace('/\S*#(?:\[[^\]]+\]|\S+)/', '', $text);

        return $text;
    }

    private function geocode($query) {
        $params = http_build_query([
            'q' => $query,
            'api_key' => config('services.geocodio.api_key')
        ]);

        return json_decode(file_get_contents('https://api.geocod.io/v1/geocode?' . $params));
    }

    private function determineCityState($text) {
        if (Str::contains($text, 'arthur')) {
            return 'Houston TX';
        } else {
            return 'Port Arthur TX';
        }
    }
}
