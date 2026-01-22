<?php

namespace App\Helpers;

/**
 * Bengali to English Transliteration Helper
 * Converts Bengali text to approximate English phonetic representation
 */
class BengaliTransliterator
{
    /**
     * Bengali consonants to English mapping
     */
    protected static array $consonants = [
        'ক' => 'k', 'খ' => 'kh', 'গ' => 'g', 'ঘ' => 'gh', 'ঙ' => 'ng',
        'চ' => 'ch', 'ছ' => 'chh', 'জ' => 'j', 'ঝ' => 'jh', 'ঞ' => 'n',
        'ট' => 't', 'ঠ' => 'th', 'ড' => 'd', 'ঢ' => 'dh', 'ণ' => 'n',
        'ত' => 't', 'থ' => 'th', 'দ' => 'd', 'ধ' => 'dh', 'ন' => 'n',
        'প' => 'p', 'ফ' => 'f', 'ব' => 'b', 'ভ' => 'bh', 'ম' => 'm',
        'য' => 'j', 'র' => 'r', 'ল' => 'l', 'শ' => 'sh', 'ষ' => 'sh',
        'স' => 's', 'হ' => 'h', 'ড়' => 'r', 'ঢ়' => 'rh', 'য়' => 'y',
        'ৎ' => 't', 'ং' => 'ng', 'ঃ' => 'h', 'ঁ' => 'n',
    ];

    /**
     * Bengali vowels to English mapping
     */
    protected static array $vowels = [
        'অ' => 'a', 'আ' => 'a', 'ই' => 'i', 'ঈ' => 'i', 'উ' => 'u',
        'ঊ' => 'u', 'ঋ' => 'ri', 'এ' => 'e', 'ঐ' => 'oi', 'ও' => 'o',
        'ঔ' => 'ou',
    ];

    /**
     * Bengali vowel signs (kar) to English mapping
     */
    protected static array $vowelSigns = [
        'া' => 'a', 'ি' => 'i', 'ী' => 'i', 'ু' => 'u', 'ূ' => 'u',
        'ৃ' => 'ri', 'ে' => 'e', 'ৈ' => 'oi', 'ো' => 'o', 'ৌ' => 'ou',
        '্' => '', // Halant - removes inherent vowel
    ];

    /**
     * Bengali numbers to English mapping
     */
    protected static array $numbers = [
        '০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4',
        '৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9',
    ];

    /**
     * Common Bengali name patterns and their English equivalents
     */
    protected static array $commonPatterns = [
        // Common prefixes
        'মোঃ' => 'Md', 'মোছাঃ' => 'Mst', 'মোসাঃ' => 'Mst', 'মো:' => 'Md',
        'ড.' => 'Dr', 'ড:' => 'Dr', 'ডাঃ' => 'Dr',
        'শেখ' => 'Sheikh', 'শেখা' => 'Shekha',
        'মীর' => 'Mir', 'মির' => 'Mir',
        'আবু' => 'Abu', 'আবূ' => 'Abu',
        'বকর' => 'Bakar', 'বকর' => 'Bakar',
        'সিদ্দিক' => 'Siddique', 'সিদ্দিকী' => 'Siddiqui', 'ছিদ্দিক' => 'Siddique',
        'রহমান' => 'Rahman', 'রহিম' => 'Rahim',
        'করিম' => 'Karim', 'কবির' => 'Kabir',
        'আব্দুল' => 'Abdul', 'আব্দুর' => 'Abdur',
        'হোসেন' => 'Hossain', 'হোসাইন' => 'Hossain', 'হুসেন' => 'Hussain',
        'আহমেদ' => 'Ahmed', 'আহমদ' => 'Ahmad', 'আহাম্মদ' => 'Ahammad',
        'আলী' => 'Ali', 'আলি' => 'Ali',
        'খান' => 'Khan', 'চৌধুরী' => 'Chowdhury', 'চৌধুরি' => 'Chowdhury',
        'বেগম' => 'Begum', 'বিবি' => 'Bibi',
        'খাতুন' => 'Khatun', 'খাতূন' => 'Khatun',
        'সরকার' => 'Sarkar', 'মল্লিক' => 'Mallick', 'মোল্লা' => 'Molla',
        'প্রামাণিক' => 'Pramanik', 'মন্ডল' => 'Mondol', 'বিশ্বাস' => 'Biswas',
        'দাস' => 'Das', 'ঘোষ' => 'Ghosh', 'রায়' => 'Roy', 'পাল' => 'Pal',
        'সাহা' => 'Saha', 'নাথ' => 'Nath', 'দেব' => 'Deb',
        'আক্তার' => 'Akter', 'আখতার' => 'Akhter',
        'সুলতানা' => 'Sultana', 'পারভীন' => 'Parveen', 'পারভিন' => 'Parvin',
        'ফাতেমা' => 'Fatema', 'ফাতিমা' => 'Fatima',
        'জামান' => 'Zaman', 'জামাল' => 'Jamal',
        'ইসলাম' => 'Islam', 'মুসলিম' => 'Muslim',
        'আনোয়ার' => 'Anowar', 'মনির' => 'Monir', 'মুনির' => 'Munir',
        'আমিন' => 'Amin', 'আমিনা' => 'Amina',
        'নূর' => 'Nur', 'নুর' => 'Nur',
        'উদ্দিন' => 'Uddin', 'উদ্দীন' => 'Uddin',
        'আলম' => 'Alam', 'আলাম' => 'Alam',
        'হক' => 'Haque', 'হক়' => 'Haque',
        'মাহমুদ' => 'Mahmud', 'মাহমূদ' => 'Mahmud',
        'রশিদ' => 'Rashid', 'রশীদ' => 'Rashid', 'রাশেদ' => 'Rashed',
        'সাদিক' => 'Sadiq', 'সাদেক' => 'Sadek',
        'নাসির' => 'Nasir', 'নাসের' => 'Naser',
        'হামিদ' => 'Hamid', 'হামীদ' => 'Hamid',
        'সালাম' => 'Salam', 'সেলিম' => 'Selim',
        'কাদের' => 'Qader', 'কাদির' => 'Qadir',
        'শফিক' => 'Shafiq', 'তোফায়েল' => 'Tofayel',
        'মজিদ' => 'Mojid', 'মজিবুর' => 'Mojibur',
        'রাজ্জাক' => 'Razzaq', 'রাজ্জাক়' => 'Razzaq',
        'মতিন' => 'Matin', 'মতীন' => 'Matin',
        'শহিদ' => 'Shahid', 'শহীদ' => 'Shahid',
        'ওয়াহিদ' => 'Wahid', 'জাহিদ' => 'Zahid',
        'ফরিদ' => 'Farid', 'ফেরদৌস' => 'Ferdous',
        'তারিক' => 'Tariq', 'তারেক' => 'Tareq',
        'শাকিল' => 'Shakil', 'শাহিন' => 'Shahin',
        'রাসেল' => 'Rasel', 'সোহেল' => 'Sohel',
        'বাবুল' => 'Babul', 'কামাল' => 'Kamal',
        'জাহাঙ্গীর' => 'Jahangir', 'আলতাফ' => 'Altaf',
        'লতিফ' => 'Latif', 'আতিক' => 'Atiq',
        'শামসুল' => 'Shamsul', 'মোস্তফা' => 'Mostafa',
        'মুস্তাফা' => 'Mustafa', 'মোস্তাফা' => 'Mostafa',
    ];

    /**
     * Transliterate Bengali text to English
     */
    public static function transliterate(?string $bengaliText): ?string
    {
        if (empty($bengaliText)) {
            return null;
        }

        $text = $bengaliText;

        // First, replace common patterns
        foreach (self::$commonPatterns as $bengali => $english) {
            $text = str_replace($bengali, ' ' . $english . ' ', $text);
        }

        // Replace Bengali numbers
        foreach (self::$numbers as $bengali => $english) {
            $text = str_replace($bengali, $english, $text);
        }

        // Replace vowels
        foreach (self::$vowels as $bengali => $english) {
            $text = str_replace($bengali, $english, $text);
        }

        // Replace vowel signs
        foreach (self::$vowelSigns as $bengali => $english) {
            $text = str_replace($bengali, $english, $text);
        }

        // Replace consonants (add inherent 'a' sound)
        foreach (self::$consonants as $bengali => $english) {
            // Check if followed by a vowel sign or halant
            $text = preg_replace('/' . preg_quote($bengali, '/') . '(?![ািীুূৃেৈোৌ্])/', $english . 'a', $text);
            $text = str_replace($bengali, $english, $text);
        }

        // Clean up the result
        $text = preg_replace('/\s+/', ' ', $text); // Multiple spaces to single
        $text = trim($text);

        // Capitalize first letter of each word
        $text = ucwords(strtolower($text));

        return $text;
    }

    /**
     * Convert Bengali numbers to English
     */
    public static function bengaliToEnglishNumbers(?string $text): ?string
    {
        if (empty($text)) {
            return null;
        }

        foreach (self::$numbers as $bengali => $english) {
            $text = str_replace($bengali, $english, $text);
        }

        return $text;
    }

    /**
     * Check if text contains Bengali characters
     */
    public static function containsBengali(?string $text): bool
    {
        if (empty($text)) {
            return false;
        }
        
        return preg_match('/[\x{0980}-\x{09FF}]/u', $text) === 1;
    }
}
