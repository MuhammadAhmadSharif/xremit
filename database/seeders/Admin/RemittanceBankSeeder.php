<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\RemittanceBank;
use Illuminate\Database\Seeder;

class RemittanceBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $remittance_banks = array(
            array('name' => 'Access Bank Plc','slug' => 'access-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:47:47','updated_at' => '2023-08-06 06:47:47'),
            array('name' => 'Fidelity Bank Plc','slug' => 'fidelity-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:48:00','updated_at' => '2023-08-06 06:48:00'),
            array('name' => 'First City Monument Bank Limited','slug' => 'first-city-monument-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:48:13','updated_at' => '2023-08-06 06:48:13'),
            array('name' => 'First Bank of Nigeria Limited','slug' => 'first-bank-of-nigeria-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:48:27','updated_at' => '2023-08-06 06:48:27'),
            array('name' => 'Guaranty Trust Holding Company Plc','slug' => 'guaranty-trust-holding-company-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:48:40','updated_at' => '2023-08-06 06:48:40'),
            array('name' => 'Union Bank of Nigeria Plc','slug' => 'union-bank-of-nigeria-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:48:52','updated_at' => '2023-08-06 06:48:52'),
            array('name' => 'United Bank for Africa Plc','slug' => 'united-bank-for-africa-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:49:02','updated_at' => '2023-08-06 06:49:02'),
            array('name' => 'Zenith Bank Plc','slug' => 'zenith-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:49:16','updated_at' => '2023-08-06 06:49:16'),
            array('name' => 'Citibank Nigeria Limited','slug' => 'citibank-nigeria-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:49:35','updated_at' => '2023-08-06 06:49:35'),
            array('name' => 'Ecobank Nigeria','slug' => 'ecobank-nigeria','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:49:48','updated_at' => '2023-08-06 06:49:48'),
            array('name' => 'Heritage Bank Plc','slug' => 'heritage-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:00','updated_at' => '2023-08-06 06:50:00'),
            array('name' => 'Keystone Bank Limited','slug' => 'keystone-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:14','updated_at' => '2023-08-06 06:50:14'),
            array('name' => 'Polaris Bank Limited.','slug' => 'polaris-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:40','updated_at' => '2023-08-06 06:50:40'),
            array('name' => 'Stanbic IBTC Bank Plc','slug' => 'stanbic-ibtc-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:50:52','updated_at' => '2023-08-06 06:50:52'),
            array('name' => 'Standard Chartered','slug' => 'standard-chartered','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:51:04','updated_at' => '2023-08-06 06:51:04'),
            array('name' => 'Sterling Bank Plc','slug' => 'sterling-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:51:34','updated_at' => '2023-08-06 06:51:34'),
            array('name' => 'Unity Bank Plc','slug' => 'unity-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:51:45','updated_at' => '2023-08-06 06:51:45'),
            array('name' => 'Wema Bank Plc','slug' => 'wema-bank-plc','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:51:59','updated_at' => '2023-08-06 06:51:59'),
            array('name' => 'Parallex Bank Limited','slug' => 'parallex-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:52:10','updated_at' => '2023-08-06 06:52:10'),
            array('name' => 'PremiumTrust Bank Limited','slug' => 'premiumtrust-bank-limited','country' => 'Nigeria','status' => '1','created_at' => '2023-08-06 06:52:21','updated_at' => '2023-08-06 06:52:21'),
            array('name' => 'Cooperative Bank of Kenya','slug' => 'cooperative-bank-of-kenya','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 10:45:41','updated_at' => '2023-11-02 10:45:41'),
            array('name' => 'Kenya Commercial Bank','slug' => 'kenya-commercial-bank','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 10:45:53','updated_at' => '2023-11-02 10:45:53'),
            array('name' => 'Equity Bank Kenya','slug' => 'equity-bank-kenya','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 10:46:09','updated_at' => '2023-11-02 10:46:09'),
            array('name' => 'National Bank of Kenya','slug' => 'national-bank-of-kenya','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 10:46:20','updated_at' => '2023-11-02 10:46:20'),
            array('name' => 'Absa Bank Kenya','slug' => 'absa-bank-kenya','country' => 'Kenya','status' => '1','created_at' => '2023-11-02 10:46:31','updated_at' => '2023-11-02 10:46:31'),
            array('name' => 'Banque des États de l’Afrique Centrale','slug' => 'banque-des-etats-de-lafrique-centrale','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 10:47:21','updated_at' => '2023-11-02 10:47:21'),
            array('name' => 'Bangui Cheques Postaux','slug' => 'bangui-cheques-postaux','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 10:47:31','updated_at' => '2023-11-02 10:47:31'),
            array('name' => 'Banque Internationale pour le Centrafrique (BICA)','slug' => 'banque-internationale-pour-le-centrafrique-bica','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 10:47:41','updated_at' => '2023-11-02 10:47:41'),
            array('name' => 'Banque Populaire Maroco-Centrafricaine (BPMC)','slug' => 'banque-populaire-maroco-centrafricaine-bpmc','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 10:47:50','updated_at' => '2023-11-02 10:47:50'),
            array('name' => 'Ecobank','slug' => 'ecobank','country' => 'Central African Republic','status' => '1','created_at' => '2023-11-02 10:47:59','updated_at' => '2023-11-02 10:47:59'),
            array('name' => 'United Bank for Africa','slug' => 'united-bank-for-africa','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 10:48:22','updated_at' => '2023-11-02 10:48:22'),
            array('name' => 'Bank Of Africa Senegal','slug' => 'bank-of-africa-senegal','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 10:48:36','updated_at' => '2023-11-02 10:48:36'),
            array('name' => 'Ecobank Senegal SA','slug' => 'ecobank-senegal-sa','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 10:48:46','updated_at' => '2023-11-02 10:48:46'),
            array('name' => 'Atlantic Bank Group','slug' => 'atlantic-bank-group','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 10:48:59','updated_at' => '2023-11-02 10:48:59'),
            array('name' => 'Banque Islamique du Senegal SA','slug' => 'banque-islamique-du-senegal-sa','country' => 'Senegal','status' => '1','created_at' => '2023-11-02 10:49:11','updated_at' => '2023-11-02 10:49:11'),
            array('name' => 'State Bank of India','slug' => 'state-bank-of-india','country' => 'India','status' => '1','created_at' => '2023-11-02 10:49:31','updated_at' => '2023-11-02 10:49:31'),
            array('name' => 'ICICI Bank','slug' => 'icici-bank','country' => 'India','status' => '1','created_at' => '2023-11-02 10:49:43','updated_at' => '2023-11-02 10:49:49'),
            array('name' => 'Bank of Baroda','slug' => 'bank-of-baroda','country' => 'India','status' => '1','created_at' => '2023-11-02 10:50:02','updated_at' => '2023-11-02 10:50:02'),
            array('name' => 'Canara Bank','slug' => 'canara-bank','country' => 'India','status' => '1','created_at' => '2023-11-02 10:50:13','updated_at' => '2023-11-02 10:50:13'),
            array('name' => 'Punjab National Bank','slug' => 'punjab-national-bank','country' => 'India','status' => '1','created_at' => '2023-11-02 10:50:27','updated_at' => '2023-11-02 10:50:27'),
            array('name' => 'National Bank of Pakistan','slug' => 'national-bank-of-pakistan','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 10:50:47','updated_at' => '2023-11-02 10:50:47'),
            array('name' => 'Habib Bank Limited','slug' => 'habib-bank-limited','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 10:50:58','updated_at' => '2023-11-02 10:50:58'),
            array('name' => 'Allied Bank Limited','slug' => 'allied-bank-limited','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 10:51:11','updated_at' => '2023-11-02 10:51:11'),
            array('name' => 'Bank Alfalah','slug' => 'bank-alfalah','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 10:51:24','updated_at' => '2023-11-02 10:51:24'),
            array('name' => 'United Bank Limited','slug' => 'united-bank-limited','country' => 'Pakistan','status' => '1','created_at' => '2023-11-02 10:51:39','updated_at' => '2023-11-02 10:51:39'),
            array('name' => 'Dutch Bangla Bank','slug' => 'dutch-bangla-bank','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 10:52:02','updated_at' => '2023-11-02 10:52:02'),
            array('name' => 'Islami Bank Bangladesh Ltd','slug' => 'islami-bank-bangladesh-ltd','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 10:52:22','updated_at' => '2023-11-02 10:52:29'),
            array('name' => 'BRAC Bank PLC','slug' => 'brac-bank-plc','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 10:53:48','updated_at' => '2023-11-02 10:53:48'),
            array('name' => 'Sonali Bank','slug' => 'sonali-bank','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 10:53:59','updated_at' => '2023-11-02 10:53:59'),
            array('name' => 'AB Bank Limited','slug' => 'ab-bank-limited','country' => 'Bangladesh','status' => '1','created_at' => '2023-11-02 10:54:11','updated_at' => '2023-11-02 10:54:11'),
            array('name' => 'Nepal Investment Bank','slug' => 'nepal-investment-bank','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 10:54:34','updated_at' => '2023-11-02 10:54:34'),
            array('name' => 'Nepal SBI Bank','slug' => 'nepal-sbi-bank','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 10:54:45','updated_at' => '2023-11-02 10:54:45'),
            array('name' => 'NIC ASIA Bank','slug' => 'nic-asia-bank','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 10:54:58','updated_at' => '2023-11-02 10:54:58'),
            array('name' => 'Everest Bank','slug' => 'everest-bank','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 10:55:13','updated_at' => '2023-11-02 10:55:13'),
            array('name' => 'Prabhu Bank','slug' => 'prabhu-bank','country' => 'Nepal','status' => '1','created_at' => '2023-11-02 10:55:31','updated_at' => '2023-11-02 10:55:31'),
            array('name' => 'BANQUE DE L\'UNION HAITIENNE','slug' => 'banque-de-lunion-haitienne','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 10:56:11','updated_at' => '2023-11-02 10:56:11'),
            array('name' => 'BANQUE DE LA REPUBLIQUE D\'HAITI','slug' => 'banque-de-la-republique-dhaiti','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 10:56:24','updated_at' => '2023-11-02 10:56:24'),
            array('name' => 'BANQUE DE PROMOTION COMMERCIALE ET INDUSTRIELLE S.A.','slug' => 'banque-de-promotion-commerciale-et-industrielle-sa','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 10:56:42','updated_at' => '2023-11-02 10:56:42'),
            array('name' => 'BANQUE NATIONALE DE CREDIT (BNC)','slug' => 'banque-nationale-de-credit-bnc','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 10:56:53','updated_at' => '2023-11-02 10:56:53'),
            array('name' => 'BANQUE NATIONALE DE PARIS','slug' => 'banque-nationale-de-paris','country' => 'Haiti','status' => '1','created_at' => '2023-11-02 10:57:03','updated_at' => '2023-11-02 10:57:03'),
            array('name' => 'Afghanistan International Bank','slug' => 'afghanistan-international-bank','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 10:57:52','updated_at' => '2023-11-02 10:57:52'),
            array('name' => 'Azizi Bank','slug' => 'azizi-bank','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 10:58:04','updated_at' => '2023-11-02 10:58:04'),
            array('name' => 'Bank Alfalah Limited','slug' => 'bank-alfalah-limited','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 10:58:13','updated_at' => '2023-11-02 10:58:13'),
            array('name' => 'Banke Millie Afghan','slug' => 'banke-millie-afghan','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 10:58:21','updated_at' => '2023-11-02 10:58:21'),
            array('name' => 'Bank-e-Millie Afghan','slug' => 'bank-e-millie-afghan','country' => 'Afghanistan','status' => '1','created_at' => '2023-11-02 10:58:29','updated_at' => '2023-11-02 10:58:29'),
            array('name' => 'Bank of Khartoum','slug' => 'bank-of-khartoum','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 10:58:51','updated_at' => '2023-11-02 10:58:51'),
            array('name' => 'Commercial Bank of Ethiopia','slug' => 'commercial-bank-of-ethiopia','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 10:59:01','updated_at' => '2023-11-02 10:59:01'),
            array('name' => 'Cooperative Bank of South Sudan','slug' => 'cooperative-bank-of-south-sudan','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 10:59:13','updated_at' => '2023-11-02 10:59:13'),
            array('name' => 'Qatar National Bank','slug' => 'qatar-national-bank','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 10:59:27','updated_at' => '2023-11-02 10:59:27'),
            array('name' => 'National Bank of Sudan','slug' => 'national-bank-of-sudan','country' => 'Sudan','status' => '1','created_at' => '2023-11-02 10:59:40','updated_at' => '2023-11-02 10:59:40'),
            array('name' => 'Investec','slug' => 'investec','country' => 'South Africa','status' => '1','created_at' => '2024-05-07 11:48:56','updated_at' => '2024-05-07 11:48:56'),
            array('name' => 'African Bank Limited','slug' => 'african-bank-limited','country' => 'South Africa','status' => '1','created_at' => '2024-05-07 11:49:09','updated_at' => '2024-05-07 11:49:09'),

            array('name' => 'Land Bank of the Philippines (LBP)','slug' => 'land-bank-of-the-philippines-lbp','country' => 'Philippines','status' => '1','created_at' => '2024-05-07 10:16:42','updated_at' => '2024-05-07 10:16:42'),
            array('name' => 'Metropolitan Bank and Trust Company (Metrobank)','slug' => 'metropolitan-bank-and-trust-company-metrobank','country' => 'Philippines','status' => '1','created_at' => '2024-05-07 10:16:57','updated_at' => '2024-05-07 10:16:57'),
            array('name' => 'International Commercial Bank I.C.B. Benin','slug' => 'international-commercial-bank-icb-benin','country' => 'Benin','status' => '1','created_at' => '2024-05-07 10:20:15','updated_at' => '2024-05-07 10:20:15'),
            array('name' => 'Financial Bank Benin','slug' => 'financial-bank-benin','country' => 'Benin','status' => '1','created_at' => '2024-05-07 10:20:31','updated_at' => '2024-05-07 10:20:31'),
            array('name' => 'Central Bank of Brazil','slug' => 'central-bank-of-brazil','country' => 'Brazil','status' => '1','created_at' => '2024-05-07 10:22:03','updated_at' => '2024-05-07 10:22:03'),
            array('name' => 'Banco do Brasil','slug' => 'banco-do-brasil','country' => 'Brazil','status' => '1','created_at' => '2024-05-07 10:22:19','updated_at' => '2024-05-07 10:22:19'),
            array('name' => 'Bank of Africa','slug' => 'bank-of-africa','country' => 'Burkina Faso','status' => '1','created_at' => '2024-05-07 10:23:25','updated_at' => '2024-05-07 10:23:25'),
            array('name' => 'Banque Atlantique Burkina Faso','slug' => 'banque-atlantique-burkina-faso','country' => 'Burkina Faso','status' => '1','created_at' => '2024-05-07 10:23:51','updated_at' => '2024-05-07 10:23:51'),
            array('name' => 'AEON Specialized Bank (Cambodia) PLC','slug' => 'aeon-specialized-bank-cambodia-plc','country' => 'Cambodia','status' => '1','created_at' => '2024-05-07 10:25:11','updated_at' => '2024-05-07 10:25:11'),
            array('name' => 'Agribank Cambodia Branch','slug' => 'agribank-cambodia-branch','country' => 'Cambodia','status' => '1','created_at' => '2024-05-07 10:25:27','updated_at' => '2024-05-07 10:25:27'),
            array('name' => 'Commercial Bank Cameroon','slug' => 'commercial-bank-cameroon','country' => 'Cameroon','status' => '1','created_at' => '2024-05-07 10:58:39','updated_at' => '2024-05-07 10:58:39'),
            array('name' => 'BGFI Bank Cameroon','slug' => 'bgfi-bank-cameroon','country' => 'Cameroon','status' => '1','created_at' => '2024-05-07 11:01:23','updated_at' => '2024-05-07 11:01:23'),
            array('name' => 'BBVA Colombia','slug' => 'bbva-colombia','country' => 'Colombia','status' => '1','created_at' => '2024-05-07 11:03:30','updated_at' => '2024-05-07 11:03:30'),
            array('name' => 'Banco Colpatria','slug' => 'banco-colpatria','country' => 'Colombia','status' => '1','created_at' => '2024-05-07 11:03:46','updated_at' => '2024-05-07 11:03:46'),
            array('name' => 'Central Bank of the Dominican Republic','slug' => 'central-bank-of-the-dominican-republic','country' => 'Dominican Republic','status' => '1','created_at' => '2024-05-07 11:04:24','updated_at' => '2024-05-07 11:04:24'),
            array('name' => 'Banco Popular Dominicano','slug' => 'banco-popular-dominicano','country' => 'Dominican Republic','status' => '1','created_at' => '2024-05-07 11:04:37','updated_at' => '2024-05-07 11:04:37'),
            array('name' => 'National Bank of Egypt','slug' => 'national-bank-of-egypt','country' => 'Egypt','status' => '1','created_at' => '2024-05-07 11:05:18','updated_at' => '2024-05-07 11:05:24'),
            array('name' => 'Banque Misr','slug' => 'banque-misr','country' => 'Egypt','status' => '1','created_at' => '2024-05-07 11:05:37','updated_at' => '2024-05-07 11:05:37'),
            array('name' => 'Nib International Bank','slug' => 'nib-international-bank','country' => 'Ethiopia','status' => '1','created_at' => '2024-05-07 11:06:15','updated_at' => '2024-05-07 11:06:15'),
            array('name' => 'Abay Bank','slug' => 'abay-bank','country' => 'Ethiopia','status' => '1','created_at' => '2024-05-07 11:06:29','updated_at' => '2024-05-07 11:06:29'),
            array('name' => 'The Bank of Guatemala','slug' => 'the-bank-of-guatemala','country' => 'Guatemala','status' => '1','created_at' => '2024-05-07 11:06:55','updated_at' => '2024-05-07 11:06:55'),
            array('name' => 'Bancredit','slug' => 'bancredit','country' => 'Guatemala','status' => '1','created_at' => '2024-05-07 11:07:57','updated_at' => '2024-05-07 11:07:57'),
            array('name' => 'UBA Guinée (UBA)','slug' => 'uba-guinee-uba','country' => 'Guinea','status' => '1','created_at' => '2024-05-07 11:08:22','updated_at' => '2024-05-07 11:08:22'),
            array('name' => 'Afriland First Bank Guinée.','slug' => 'afriland-first-bank-guinee','country' => 'Guinea','status' => '1','created_at' => '2024-05-07 11:08:33','updated_at' => '2024-05-07 11:08:33'),
            array('name' => 'Bank Audi','slug' => 'bank-audi','country' => 'Lebanon','status' => '1','created_at' => '2024-05-07 11:09:00','updated_at' => '2024-05-07 11:09:00'),
            array('name' => 'Byblos Bank','slug' => 'byblos-bank','country' => 'Lebanon','status' => '1','created_at' => '2024-05-07 11:09:13','updated_at' => '2024-05-07 11:09:13'),
            array('name' => 'Afriland First Bank Liberia','slug' => 'afriland-first-bank-liberia','country' => 'Liberia','status' => '1','created_at' => '2024-05-07 11:09:38','updated_at' => '2024-05-07 11:09:38'),
            array('name' => 'AccessBank Liberia','slug' => 'accessbank-liberia','country' => 'Liberia','status' => '1','created_at' => '2024-05-07 11:09:48','updated_at' => '2024-05-07 11:09:48'),
            array('name' => 'Bank Atlantic Mali','slug' => 'bank-atlantic-mali','country' => 'Mali','status' => '1','created_at' => '2024-05-07 11:10:12','updated_at' => '2024-05-07 11:10:12'),
            array('name' => 'Banque Atlantique Mali','slug' => 'banque-atlantique-mali','country' => 'Mali','status' => '1','created_at' => '2024-05-07 11:10:43','updated_at' => '2024-05-07 11:10:43'),
            array('name' => 'Banorte','slug' => 'banorte','country' => 'Mexico','status' => '1','created_at' => '2024-05-07 11:11:05','updated_at' => '2024-05-07 11:11:05'),
            array('name' => 'Banamex','slug' => 'banamex','country' => 'Mexico','status' => '1','created_at' => '2024-05-07 11:11:21','updated_at' => '2024-05-07 11:11:21'),
            array('name' => 'Attijariwafa Bank','slug' => 'attijariwafa-bank','country' => 'Morocco','status' => '1','created_at' => '2024-05-07 11:11:51','updated_at' => '2024-05-07 11:11:51'),
            array('name' => 'Banque Populaire','slug' => 'banque-populaire','country' => 'Morocco','status' => '1','created_at' => '2024-05-07 11:12:02','updated_at' => '2024-05-07 11:12:02'),
            array('name' => 'Absa Bank Mozambique','slug' => 'absa-bank-mozambique','country' => 'Mozambique','status' => '1','created_at' => '2024-05-07 11:12:29','updated_at' => '2024-05-07 11:12:29'),
            array('name' => 'Banco Comercial e de Investimentos','slug' => 'banco-comercial-e-de-investimentos','country' => 'Mozambique','status' => '1','created_at' => '2024-05-07 11:12:48','updated_at' => '2024-05-07 11:12:48'),
            array('name' => 'DFCC Bank','slug' => 'dfcc-bank','country' => 'Sri Lanka','status' => '1','created_at' => '2024-05-07 11:13:12','updated_at' => '2024-05-07 11:13:12'),
            array('name' => 'Amana Bank','slug' => 'amana-bank','country' => 'Sri Lanka','status' => '1','created_at' => '2024-05-07 11:13:28','updated_at' => '2024-05-07 11:13:28'),
            array('name' => 'Absa Bank Tanzania','slug' => 'absa-bank-tanzania','country' => 'Tanzania','status' => '1','created_at' => '2024-05-07 11:13:58','updated_at' => '2024-05-07 11:13:58'),
            array('name' => 'Akiba Commercial Bank','slug' => 'akiba-commercial-bank','country' => 'Tanzania','status' => '1','created_at' => '2024-05-07 11:14:13','updated_at' => '2024-05-07 11:14:13'),
            array('name' => 'Ziraat Bank','slug' => 'ziraat-bank','country' => 'Turkey','status' => '1','created_at' => '2024-05-07 11:14:34','updated_at' => '2024-05-07 11:14:34'),
            array('name' => 'Akbank','slug' => 'akbank','country' => 'Turkey','status' => '1','created_at' => '2024-05-07 11:14:47','updated_at' => '2024-05-07 11:14:47'),
            array('name' => 'Cairo Bank Uganda','slug' => 'cairo-bank-uganda','country' => 'Uganda','status' => '1','created_at' => '2024-05-07 11:15:10','updated_at' => '2024-05-07 11:15:10'),
            array('name' => 'Citibank Uganda','slug' => 'citibank-uganda','country' => 'Uganda','status' => '1','created_at' => '2024-05-07 11:15:32','updated_at' => '2024-05-07 11:15:32'),
            array('name' => 'Bank for Investment and Development of Vietnam','slug' => 'bank-for-investment-and-development-of-vietnam','country' => 'Vietnam','status' => '1','created_at' => '2024-05-07 11:16:16','updated_at' => '2024-05-07 11:16:16'),
            array('name' => 'Joint Stock Commercial Bank for Foreign Trade of Vietnam','slug' => 'joint-stock-commercial-bank-for-foreign-trade-of-vietnam','country' => 'Vietnam','status' => '1','created_at' => '2024-05-07 11:16:30','updated_at' => '2024-05-07 11:16:30'),
            array('name' => 'Access Bank Zambia','slug' => 'access-bank-zambia','country' => 'Zambia','status' => '1','created_at' => '2024-05-07 11:16:53','updated_at' => '2024-05-07 11:16:53'),
            array('name' => 'Stanbic Bank','slug' => 'stanbic-bank','country' => 'Zambia','status' => '1','created_at' => '2024-05-07 11:17:06','updated_at' => '2024-05-07 11:17:06'),
            array('name' => 'Reserve Bank of Zimbabwe','slug' => 'reserve-bank-of-zimbabwe','country' => 'Zimbabwe','status' => '1','created_at' => '2024-05-07 11:17:27','updated_at' => '2024-05-07 11:17:27'),
            array('name' => 'ZB BANK','slug' => 'zb-bank','country' => 'Zimbabwe','status' => '1','created_at' => '2024-05-07 11:17:40','updated_at' => '2024-05-07 11:17:40')
          
        );

        foreach($remittance_banks as $item){
            $bank   = RemittanceBank::where('name',$item['name'])->where('country',$item['country'])->first();
            if($bank == null){
                RemittanceBank::insert($item);
            }
        }

        
    }
}
