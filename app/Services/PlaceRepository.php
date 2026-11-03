<?php

namespace App\Services;

class PlaceRepository
{
    public static function getCategories(): array
    {
        return [
            [
                'id' => 'all',
                'name' => 'All Places',
                'icon' => 'layout-grid',
                'count' => 12,
                'description' => 'Browse everything on and around campus',
            ],
            [
                'id' => 'campus-facilities',
                'name' => 'Campus Facilities',
                'icon' => 'landmark',
                'count' => 6,
                'description' => 'Libraries, departments, auditoriums & labs',
            ],
            [
                'id' => 'food-drinks',
                'name' => 'Food & Drinks',
                'icon' => 'utensils',
                'count' => 5,
                'description' => 'Student mess, canteens, street bites & snacks',
            ],
            [
                'id' => 'cafes',
                'name' => 'Cafes',
                'icon' => 'coffee',
                'count' => 2,
                'description' => 'Coffee spots, study hangouts & tea corners',
            ],
            [
                'id' => 'grocery',
                'name' => 'Grocery & Daily Needs',
                'icon' => 'shopping-bag',
                'count' => 2,
                'description' => 'General stores, fresh fruits & hostel essentials',
            ],
            [
                'id' => 'restaurants',
                'name' => 'Restaurants',
                'icon' => 'store',
                'count' => 3,
                'description' => 'Full meals, family dining & weekend dinner spots',
            ],
            [
                'id' => 'shops',
                'name' => 'Shops & Services',
                'icon' => 'printer',
                'count' => 2,
                'description' => 'Stationery, xerox, printing & repair shops',
            ],
            [
                'id' => 'transportation',
                'name' => 'Transportation',
                'icon' => 'bus',
                'count' => 2,
                'description' => 'Auto stands, e-rickshaw points & shuttle stops',
            ],
        ];
    }

    public static function getAllPlaces(): array
    {
        return [
            // Campus Facilities
            [
                'slug' => 'central-library',
                'name' => 'Central Library',
                'category' => 'Campus Facilities',
                'category_id' => 'campus-facilities',
                'is_campus' => true,
                'tagline' => 'A multi-story academic repository with quiet reading halls and high-speed Wi-Fi.',
                'description' => 'The Central Library at IIT Patna houses thousands of physical volumes, electronic journals, and digital research terminals. It features dedicated quiet zones, group collaboration rooms, air conditioning, and ample power outlets for long study sessions.',
                'location_label' => 'Inside Campus • Central Academic Area',
                'distance' => 'Campus Core',
                'hours' => '8:00 AM – 12:00 Midnight (Extended during exams)',
                'coordinates' => [25.5358, 84.8515],
                'tags' => ['Air Conditioned', 'High Speed WiFi', 'Quiet Study Zone', 'Digital Terminals'],
                'image' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=900&auto=format&fit=crop',
                'featured' => true,
                'status_indicator' => 'Open Now',
            ],
            [
                'slug' => 'academic-block-9',
                'name' => 'Tutorial Block 9 (Lecture Complex)',
                'category' => 'Campus Facilities',
                'category_id' => 'campus-facilities',
                'is_campus' => true,
                'tagline' => 'Primary lecture theatres, smart presentation halls, and department offices.',
                'description' => 'The Tutorial and Lecture Complex hosts large undergraduate lectures, seminar sessions, and academic conferences. Equipped with tiered seating, acoustic panelling, modern audiovisual projectors, and accessible corridors.',
                'location_label' => 'Inside Campus • North Academic Spine',
                'distance' => '300m from Admin Building',
                'hours' => '7:30 AM – 8:00 PM',
                'coordinates' => [25.5372, 84.8528],
                'tags' => ['Smart Classrooms', 'Acoustic Halls', 'Wheelchair Accessible'],
                'image' => 'https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=900&auto=format&fit=crop',
                'featured' => true,
                'status_indicator' => 'Classes Active',
            ],
            [
                'slug' => 'senate-hall-auditorium',
                'name' => 'Main Auditorium & Senate Hall',
                'category' => 'Campus Facilities',
                'category_id' => 'campus-facilities',
                'is_campus' => true,
                'tagline' => 'Cultural events, guest lectures, convocation, and festival venue.',
                'description' => 'The prestigious main hall accommodating over 1,000 attendees. It serves as the centerpiece for Anwesha (cultural fest), Celeste (tech fest), guest summits, theatrical performances, and institute ceremonies.',
                'location_label' => 'Inside Campus • South Plaza',
                'distance' => '450m from Hostels',
                'hours' => 'Event Dependent',
                'coordinates' => [25.5345, 84.8505],
                'tags' => ['1000+ Seating', 'Surround Audio', 'Stage Lighting', 'Central AC'],
                'image' => 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?q=80&w=900&auto=format&fit=crop',
                'featured' => false,
                'status_indicator' => 'Events Only',
            ],
            [
                'slug' => 'gymkhana-sports-complex',
                'name' => 'Gymkhana Sports Arena',
                'category' => 'Campus Facilities',
                'category_id' => 'campus-facilities',
                'is_campus' => true,
                'tagline' => 'Floodlit basketball courts, athletic track, gym, and badminton arenas.',
                'description' => 'The heart of campus athletics and physical recreation. Includes synthetic badminton courts, Olympic-standard basketball courts, modern fitness equipment, squash courts, and floodlit cricket/football grounds.',
                'location_label' => 'Inside Campus • East Sports Zone',
                'distance' => '250m from Boys Hostels',
                'hours' => '6:00 AM – 9:30 AM & 4:30 PM – 9:00 PM',
                'coordinates' => [25.5325, 84.8535],
                'tags' => ['Floodlit Courts', 'Indoor Gym', 'Equipment Locker', 'Drinking Water'],
                'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=900&auto=format&fit=crop',
                'featured' => true,
                'status_indicator' => 'Active',
            ],
            [
                'slug' => 'central-dining-hall',
                'name' => 'Central Dining & Night Canteen',
                'category' => 'Food & Drinks',
                'category_id' => 'food-drinks',
                'is_campus' => true,
                'tagline' => 'Multi-cuisine student mess, juice counter, and late-night snacks counter.',
                'description' => 'Providing daily meals to the student community alongside an attached night canteen serving hot parathas, maggi, coffee, and fresh fruit juices until early morning hours.',
                'location_label' => 'Inside Campus • Hostel Cluster',
                'distance' => 'Adjacent to Hostel Block C',
                'hours' => '7:00 AM – 10:00 PM (Night Canteen open till 2:00 AM)',
                'coordinates' => [25.5338, 84.8492],
                'tags' => ['Late Night Food', 'Student Budget', 'Veg & Non-Veg', 'UPI Accepted'],
                'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=900&auto=format&fit=crop',
                'featured' => true,
                'status_indicator' => 'Serving Food',
            ],
            [
                'slug' => 'student-activity-center',
                'name' => 'Student Activity Center (SAC)',
                'category' => 'Campus Facilities',
                'category_id' => 'campus-facilities',
                'is_campus' => true,
                'tagline' => 'Club rooms, robotics garage, music practice studio, and open amphitheatre.',
                'description' => 'Where student initiatives come to life. House to tech clubs, music and dance societies, literary cell, and an outdoor courtyard where hackathons and informal jams take place.',
                'location_label' => 'Inside Campus • Student Quad',
                'distance' => 'Near Library Lawn',
                'hours' => '24/7 Access with ID card',
                'coordinates' => [25.5318, 84.8510],
                'tags' => ['Jam Room', 'Project Lab', 'Outdoor Seating', 'Student Clubs'],
                'image' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=900&auto=format&fit=crop',
                'featured' => false,
                'status_indicator' => 'Open 24/7',
            ],

            // Nearby Places (Outside Campus / Bihta area)
            [
                'slug' => 'gate-1-hot-momos',
                'name' => 'Gate 1 Hot Momos & Maggi Point',
                'category' => 'Food & Drinks',
                'category_id' => 'food-drinks',
                'is_campus' => false,
                'tagline' => 'Legendary evening steamed & fried momos, spicy red chutney, and cheese maggi.',
                'description' => 'A staple evening destination for IIT Patna students stepping out of Gate 1. Known for steaming hot paneer and chicken momos, freshly rolled rolls, and special masala chai that warms up winter study breaks.',
                'location_label' => 'Outside Campus • Gate 1 Market Lane',
                'distance' => '150m from Gate 1',
                'hours' => '4:00 PM – 10:30 PM',
                'coordinates' => [25.5390, 84.8538],
                'tags' => ['Student Favorite', 'Quick Bites', 'Pocket Friendly', 'Cashless UPI'],
                'image' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?q=80&w=900&auto=format&fit=crop',
                'featured' => true,
                'status_indicator' => 'Popular Evening Spot',
            ],
            [
                'slug' => 'the-green-oasis-cafe',
                'name' => 'The Green Oasis Cafe & Bakery',
                'category' => 'Cafes',
                'category_id' => 'cafes',
                'is_campus' => false,
                'tagline' => 'Specialty cold brews, pastries, sandwiches, and relaxing indoor study seating.',
                'description' => 'A cozy cafe just minutes away from campus, featuring air conditioning, warm wooden decor, artisanal espresso drinks, fresh pastries, and plenty of power outlets for laptop work.',
                'location_label' => 'Bihta Main Road • Near Reliance Petrol Pump',
                'distance' => '750m from Main Gate',
                'hours' => '10:00 AM – 10:00 PM',
                'coordinates' => [25.5395, 84.8550],
                'tags' => ['Work Friendly', 'Good Coffee', 'Free WiFi', 'Air Conditioned'],
                'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=900&auto=format&fit=crop',
                'featured' => true,
                'status_indicator' => 'Open Now',
            ],
            [
                'slug' => 'bihta-junction-chai-samosa',
                'name' => 'Bihta Junction Chai & Samosa Stall',
                'category' => 'Food & Drinks',
                'category_id' => 'food-drinks',
                'is_campus' => false,
                'tagline' => 'Crispy hot samosas, jalebi in the morning, and clay-cup kulhad chai.',
                'description' => 'Iconic roadside refreshment hub known across the student community for fresh morning kachoris, crispy samosas, and strong cardamom ginger tea served in earthen kulhads.',
                'location_label' => 'Bihta Station Road • Periphery',
                'distance' => '1.2 km from Main Gate',
                'hours' => '6:00 AM – 8:30 PM',
                'coordinates' => [25.5415, 84.8560],
                'tags' => ['Authentic Kulhad Chai', 'Fresh Morning Breakfast', 'Super Budget'],
                'image' => 'https://images.unsplash.com/photo-1561047029-3000c68339ca?q=80&w=900&auto=format&fit=crop',
                'featured' => false,
                'status_indicator' => 'Open from 6 AM',
            ],
            [
                'slug' => 'patliputra-thali-restaurant',
                'name' => 'Patliputra Thali & Family Diner',
                'category' => 'Restaurants',
                'category_id' => 'restaurants',
                'is_campus' => false,
                'tagline' => 'Unlimited North Indian thalis, paneer curries, and weekend feast meals.',
                'description' => 'When students crave a hearty full-course meal with friends or visiting parents. Features air-conditioned dining, hygienic kitchen, diverse vegetarian & non-vegetarian thalis, and quick service.',
                'location_label' => 'Bihta Market Chowk',
                'distance' => '1.4 km from Campus Gate',
                'hours' => '11:30 AM – 3:30 PM & 6:30 PM – 10:30 PM',
                'coordinates' => [25.5420, 84.8575],
                'tags' => ['Group Dining', 'Unlimited Thali', 'Clean Restrooms', 'AC Hall'],
                'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=900&auto=format&fit=crop',
                'featured' => true,
                'status_indicator' => 'Dinner Ready',
            ],
            [
                'slug' => 'maa-durga-daily-grocery',
                'name' => 'Maa Durga Daily Grocery & Essentials',
                'category' => 'Grocery',
                'category_id' => 'grocery',
                'is_campus' => false,
                'tagline' => 'Packaged snacks, dairy, toiletries, fruits, and hostel living essentials.',
                'description' => 'One-stop department and convenience shop stocked with all student daily necessities: instant noodles, milk, bread, laundry detergents, batteries, room fresheners, and toiletries.',
                'location_label' => 'Opposite Gate 1 • Commercial Complex',
                'distance' => '200m from Gate 1',
                'hours' => '8:00 AM – 10:00 PM',
                'coordinates' => [25.5408, 84.8542],
                'tags' => ['Daily Essentials', 'Packed Snacks', 'UPI & Card', 'Hostel Friendly'],
                'image' => 'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?q=80&w=900&auto=format&fit=crop',
                'featured' => false,
                'status_indicator' => 'In Stock',
            ],
            [
                'slug' => 'student-print-stationery-hub',
                'name' => 'Student Print, Xerox & Stationery Point',
                'category' => 'Shops',
                'category_id' => 'shops',
                'is_campus' => false,
                'tagline' => 'High-speed project printing, spiral binding, report covers, and stationery.',
                'description' => 'Lifesaver for assignment deadlines and thesis submissions. Offers high-speed laser printing, A0/A1 poster plots for seminars, soft and hardcover project binding, and quality notebooks.',
                'location_label' => 'Outside Gate 2 • Market Corner',
                'distance' => '250m from Gate 2',
                'hours' => '8:30 AM – 9:30 PM (Late night during submission week)',
                'coordinates' => [25.5385, 84.8545],
                'tags' => ['Project Binding', 'Laser Color Print', 'Stationery', 'Fast Turnaround'],
                'image' => 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?q=80&w=900&auto=format&fit=crop',
                'featured' => false,
                'status_indicator' => 'Printers Ready',
            ],
        ];
    }

    public static function getCampusPlaces(): array
    {
        return array_values(array_filter(self::getAllPlaces(), fn($place) => $place['is_campus'] === true));
    }

    public static function getNearbyPlaces(): array
    {
        return array_values(array_filter(self::getAllPlaces(), fn($place) => $place['is_campus'] === false));
    }

    public static function getFeaturedPlaces(): array
    {
        return array_values(array_filter(self::getAllPlaces(), fn($place) => !empty($place['featured'])));
    }

    public static function findPlaceBySlug(string $slug): ?array
    {
        foreach (self::getAllPlaces() as $place) {
            if ($place['slug'] === $slug) {
                return $place;
            }
        }
        return null;
    }

    public static function getRelatedPlaces(string $currentSlug, int $limit = 3): array
    {
        $current = self::findPlaceBySlug($currentSlug);
        $all = self::getAllPlaces();
        
        $related = array_filter($all, function ($place) use ($currentSlug, $current) {
            if ($place['slug'] === $currentSlug) {
                return false;
            }
            if ($current && $place['category_id'] === $current['category_id']) {
                return true;
            }
            return false;
        });

        if (count($related) < $limit) {
            foreach ($all as $place) {
                if ($place['slug'] !== $currentSlug && !in_array($place, $related, true)) {
                    $related[] = $place;
                }
                if (count($related) >= $limit) {
                    break;
                }
            }
        }

        return array_slice(array_values($related), 0, $limit);
    }
}
