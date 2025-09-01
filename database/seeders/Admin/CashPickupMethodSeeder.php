<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\CashPickup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashPickupMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cash_pickups = array(
            array('address' => 'Federal Secretariat Complex, Phase 1, Annex II, Ground Floor Shehu Shagari Way, Maitama P.M.B. 406 Garki Abuja','slug' => '','country' => 'Nigeria','status' => '1','created_at' => '2024-06-20 06:48:15','updated_at' => '2024-06-20 09:20:02'),
            array('address' => 'Block ‘D’ Old Secretariat Garki, Abuja','slug' => 'block-d-old-secretariat-garki-abuja','country' => 'Nigeria','status' => '1','created_at' => '2024-06-20 09:21:14','updated_at' => '2024-06-20 09:21:14'),
            array('address' => '5th Floor NHIF Building Ragati Road P.O Box 34670 - 00100 Nairobi - Kenya','slug' => '5th-floor-nhif-building-ragati-road-po-box-34670-00100-nairobi-kenya','country' => 'Kenya','status' => '1','created_at' => '2024-06-20 09:21:44','updated_at' => '2024-06-20 09:21:44'),
            array('address' => 'KIPI Centre 17 Kabarsiran Avenue, Off Waiyaki Way Lavington Nairobi','slug' => 'kipi-centre-17-kabarsiran-avenue-off-waiyaki-way-lavington-nairobi','country' => 'Kenya','status' => '1','created_at' => '2024-06-20 09:21:56','updated_at' => '2024-06-20 09:21:56'),
            array('address' => 'Ministère des Arts, de la Culture et du Tourisme B.P. 655 Bangui','slug' => 'ministere-des-arts-de-la-culture-et-du-tourisme-bp-655-bangui','country' => 'Central African Republic','status' => '1','created_at' => '2024-06-20 09:22:44','updated_at' => '2024-06-20 09:22:44'),
            array('address' => 'BP 1988 Bangui- RCA','slug' => 'bp-1988-bangui-rca','country' => 'Central African Republic','status' => '1','created_at' => '2024-06-20 09:23:05','updated_at' => '2024-06-20 09:23:05'),
            array('address' => '7 Rue Saint-Michel Ngalandou Diouf Dakar, B.P. 126 Sénégal','slug' => '7-rue-saint-michel-ngalandou-diouf-dakar-bp-126-senegal','country' => 'Senegal','status' => '1','created_at' => '2024-06-20 09:24:08','updated_at' => '2024-06-20 09:24:08'),
            array('address' => 'Liberté 6 Extension Nord Cité des jeunes cadres, lot n°14 VDN en face des cimetières Saint Lazare Dakar','slug' => 'liberte-6-extension-nord-cite-des-jeunes-cadres-lot-n14-vdn-en-face-des-cimetieres-saint-lazare-dakar','country' => 'Senegal','status' => '1','created_at' => '2024-06-20 09:24:22','updated_at' => '2024-06-20 09:24:22'),
            array('address' => 'B-24, Ganesh Nagar, R.c. Marg, Chembur','slug' => 'b-24-ganesh-nagar-rc-marg-chembur','country' => 'India','status' => '1','created_at' => '2024-06-20 09:34:18','updated_at' => '2024-06-20 09:34:18'),
            array('address' => 'Venilal Estate, 34 Dr Am Road, Nr Kabuter Khana, Bhuleshwar','slug' => 'venilal-estate-34-dr-am-road-nr-kabuter-khana-bhuleshwar','country' => 'India','status' => '1','created_at' => '2024-06-20 09:34:25','updated_at' => '2024-06-20 09:34:25'),
            array('address' => 'Chouburji Cent.Multan Road,Lahor','slug' => 'chouburji-centmultan-roadlahor','country' => 'Pakistan','status' => '1','created_at' => '2024-06-20 09:35:00','updated_at' => '2024-06-20 09:35:00'),
            array('address' => 'Gound Floor,Blessing Homes,Nazimabad #2','slug' => 'gound-floorblessing-homesnazimabad-2','country' => 'Pakistan','status' => '1','created_at' => '2024-06-20 09:35:13','updated_at' => '2024-06-20 09:35:13'),
            array('address' => '242 tejgaon i/a., 1208, Dhaka','slug' => '242-tejgaon-ia-1208-dhaka','country' => 'Bangladesh','status' => '1','created_at' => '2024-06-20 09:35:38','updated_at' => '2024-06-20 09:35:38'),
            array('address' => '225, khilgaon, tilpapara, 1219','slug' => '225-khilgaon-tilpapara-1219','country' => 'Bangladesh','status' => '1','created_at' => '2024-06-20 09:35:49','updated_at' => '2024-06-20 09:35:49'),
            array('address' => 'Baneshwor','slug' => 'baneshwor','country' => 'Nepal','status' => '1','created_at' => '2024-06-20 09:36:18','updated_at' => '2024-06-20 09:36:18'),
            array('address' => 'Maharajgunj','slug' => 'maharajgunj','country' => 'Nepal','status' => '1','created_at' => '2024-06-20 09:36:31','updated_at' => '2024-06-20 09:36:31'),
            array('address' => '31, rue Cheriez Canape-Vert Port-au-Prince HAITI','slug' => '31-rue-cheriez-canape-vert-port-au-prince-haiti','country' => 'Haiti','status' => '1','created_at' => '2024-06-20 09:37:19','updated_at' => '2024-06-20 09:37:19'),
            array('address' => 'Parc Industriel Métropolitain (SONAPI) Route de l’Aéroport Boîte Postale HT-6120 Port-au-Prince','slug' => 'parc-industriel-metropolitain-sonapi-route-de-laeroport-boite-postale-ht-6120-port-au-prince','country' => 'Haiti','status' => '1','created_at' => '2024-06-20 09:37:34','updated_at' => '2024-06-20 09:37:34'),
            array('address' => 'Afghanistan Kabul City District #8 Chaman Huzuri Kabul Nandary Area','slug' => 'afghanistan-kabul-city-district-8-chaman-huzuri-kabul-nandary-area','country' => 'Afghanistan','status' => '1','created_at' => '2024-06-20 09:38:22','updated_at' => '2024-06-20 09:38:22'),
            array('address' => 'Kabul, Province Islamic Republic of Afghanistan','slug' => 'kabul-province-islamic-republic-of-afghanistan','country' => 'Afghanistan','status' => '1','created_at' => '2024-06-20 09:38:43','updated_at' => '2024-06-20 09:38:43'),
            array('address' => 'P.O. Box Western Sudanese Radio Station, Almolazmein, Omdurman','slug' => 'po-box-western-sudanese-radio-station-almolazmein-omdurman','country' => 'Sudan','status' => '1','created_at' => '2024-06-20 09:39:13','updated_at' => '2024-06-20 09:39:13'),
            array('address' => 'Aljamhoria Street Almugran Area P.O Box 744 Khartoum - Sudan','slug' => 'aljamhoria-street-almugran-area-po-box-744-khartoum-sudan','country' => 'Sudan','status' => '1','created_at' => '2024-06-20 09:39:26','updated_at' => '2024-06-20 09:39:26'),
            array('address' => '2332 Fox St 2332 Fox St','slug' => '2332-fox-st-2332-fox-st','country' => 'South Africa','status' => '1','created_at' => '2024-06-20 09:40:14','updated_at' => '2024-06-20 09:40:14'),
            array('address' => '85 Kort St,  Bloemfontein','slug' => '85-kort-st-bloemfontein','country' => 'South Africa','status' => '1','created_at' => '2024-06-20 09:40:34','updated_at' => '2024-06-20 09:40:34'),
            array('address' => 'Governor Villar Street,San Jose','slug' => 'governor-villar-streetsan-jose','country' => 'Philippines','status' => '1','created_at' => '2024-06-20 09:41:00','updated_at' => '2024-06-20 09:41:00'),
            array('address' => '3rd Floor Escaba Building, Flores Street,San Pablo City','slug' => '3rd-floor-escaba-building-flores-streetsan-pablo-city','country' => 'Philippines','status' => '1','created_at' => '2024-06-20 09:41:20','updated_at' => '2024-06-20 09:41:20'),
            array('address' => 'Carré 784 P Bd du Canada B.P. 06-2650 Cotonou','slug' => 'carre-784-p-bd-du-canada-bp-06-2650-cotonou','country' => 'Benin','status' => '1','created_at' => '2024-06-20 09:41:53','updated_at' => '2024-06-20 09:41:53'),
            array('address' => '01 BP 363 Cotonou','slug' => '01-bp-363-cotonou','country' => 'Benin','status' => '1','created_at' => '2024-06-20 09:42:04','updated_at' => '2024-06-20 09:42:04'),
            array('address' => 'Rua Cornélio Kloster 1060,Apucarana.','slug' => 'rua-cornelio-kloster-1060apucarana','country' => 'Brazil','status' => '1','created_at' => '2024-06-20 09:43:07','updated_at' => '2024-06-20 09:43:07'),
            array('address' => 'Rua Faustina de Almeida Chiaravalotti 471,Americana','slug' => 'rua-faustina-de-almeida-chiaravalotti-471americana','country' => 'Brazil','status' => '1','created_at' => '2024-06-20 09:43:40','updated_at' => '2024-06-20 09:43:40'),
            array('address' => 'Villa de la Victoire Sis, secteur 4 au 22, rue 4.55 01 B.P. 3926 Ouagadougou 01','slug' => 'villa-de-la-victoire-sis-secteur-4-au-22-rue-455-01-bp-3926-ouagadougou-01','country' => 'Burkina Faso','status' => '1','created_at' => '2024-06-20 09:44:11','updated_at' => '2024-06-20 09:44:11'),
            array('address' => 'Boulevard des Tensoaba, 17 BP 15 Ouagadougou','slug' => 'boulevard-des-tensoaba-17-bp-15-ouagadougou','country' => 'Burkina Faso','status' => '1','created_at' => '2024-06-20 09:44:25','updated_at' => '2024-06-20 09:44:25'),
            array('address' => '#275, St.7 S/K Bos Leav, Chetr Borei District','slug' => '275-st7-sk-bos-leav-chetr-borei-district','country' => 'Cambodia','status' => '1','created_at' => '2024-06-20 09:44:45','updated_at' => '2024-06-20 09:44:45'),
            array('address' => 'National Road 7A S/K Rumchek, Kampong Siem District','slug' => 'national-road-7a-sk-rumchek-kampong-siem-district','country' => 'Cambodia','status' => '1','created_at' => '2024-06-20 09:45:03','updated_at' => '2024-06-20 09:45:03'),
            array('address' => 'Bld Ahmadou Ahidjo,Douala.','slug' => 'bld-ahmadou-ahidjodouala','country' => 'Cameroon','status' => '1','created_at' => '2024-06-20 09:45:36','updated_at' => '2024-06-20 09:45:36'),
            array('address' => 'DJEUMOUN Rue Foumbot,Bafoussam.','slug' => 'djeumoun-rue-foumbotbafoussam','country' => 'Cameroon','status' => '1','created_at' => '2024-06-20 09:46:01','updated_at' => '2024-06-20 09:46:01'),
            array('address' => 'Cr 53 No. 76-41, C.P 08001,Barranquilla.','slug' => 'cr-53-no-76-41-cp-08001barranquilla','country' => 'Colombia','status' => '1','created_at' => '2024-06-20 09:46:28','updated_at' => '2024-06-20 09:46:28'),
            array('address' => 'Cr 9 No. 24-81, C.P 66001,Pereira.','slug' => 'cr-9-no-24-81-cp-66001pereira','country' => 'Colombia','status' => '1','created_at' => '2024-06-20 09:46:45','updated_at' => '2024-06-20 09:46:45'),
            array('address' => 'Gral Cabrera 67,Santiago.','slug' => 'gral-cabrera-67santiago','country' => 'Dominican Republic','status' => '1','created_at' => '2024-06-20 09:47:16','updated_at' => '2024-06-20 09:47:16'),
            array('address' => 'C Rotario 42,Santo Domingo','slug' => 'c-rotario-42santo-domingo','country' => 'Dominican Republic','status' => '1','created_at' => '2024-06-20 09:47:37','updated_at' => '2024-06-20 09:47:37'),
            array('address' => '8 EL Bostan Street, Tahreer Sq,Cairo','slug' => '8-el-bostan-street-tahreer-sqcairo','country' => 'Egypt','status' => '1','created_at' => '2024-06-20 09:48:10','updated_at' => '2024-06-20 09:48:10'),
            array('address' => '8 Al Galaa St., Aghakhan,Shoubra.','slug' => '8-al-galaa-st-aghakhanshoubra','country' => 'Egypt','status' => '1','created_at' => '2024-06-20 09:48:37','updated_at' => '2024-06-20 09:48:37'),
            array('address' => 'Kebele 19, 16/2,Addis Ababa','slug' => 'kebele-19-162addis-ababa','country' => 'Ethiopia','status' => '1','created_at' => '2024-06-20 09:49:08','updated_at' => '2024-06-20 09:49:08'),
            array('address' => 'Bole Subcity, Haile G / Sellasie Street','slug' => 'bole-subcity-haile-g-sellasie-street','country' => 'Ethiopia','status' => '1','created_at' => '2024-06-20 09:49:24','updated_at' => '2024-06-20 09:49:24'),
            array('address' => 'Calle Chipilapa No.8D Antigua Guatemala','slug' => 'calle-chipilapa-no8d-antigua-guatemala','country' => 'Guatemala','status' => '1','created_at' => '2024-06-20 09:49:48','updated_at' => '2024-06-20 09:49:48'),
            array('address' => 'C Ancha De Los Herreros No.59a','slug' => 'c-ancha-de-los-herreros-no59a','country' => 'Guatemala','status' => '1','created_at' => '2024-06-20 09:50:02','updated_at' => '2024-06-20 09:50:02'),
            array('address' => 'B.P. 468 Conakry','slug' => 'bp-468-conakry','country' => 'Guinea','status' => '1','created_at' => '2024-06-20 09:50:43','updated_at' => '2024-06-20 09:50:43'),
            array('address' => '4ème étage Palais du peuple côté Ouest Commune de Kaloum B.P. 4904 Conakry','slug' => '4eme-etage-palais-du-peuple-cote-ouest-commune-de-kaloum-bp-4904-conakry','country' => 'Guinea','status' => '1','created_at' => '2024-06-20 09:51:04','updated_at' => '2024-06-20 09:51:04'),
            array('address' => '3rd floor, Fakhri & Taher building, Bliss street,Jamia Sector.','slug' => '3rd-floor-fakhri-taher-building-bliss-streetjamia-sector','country' => 'Lebanon','status' => '1','created_at' => '2024-06-20 09:51:43','updated_at' => '2024-06-20 09:51:43'),
            array('address' => 'Mar Elias st, Mazraa,Beirut','slug' => 'mar-elias-st-mazraabeirut','country' => 'Lebanon','status' => '1','created_at' => '2024-06-20 09:52:10','updated_at' => '2024-06-20 09:52:10'),
            array('address' => 'U.N.Drive Old Labour Ministry Compound Monrovia','slug' => 'undrive-old-labour-ministry-compound-monrovia','country' => 'Liberia','status' => '1','created_at' => '2024-06-20 09:53:02','updated_at' => '2024-06-20 09:53:02'),
            array('address' => 'Bp. 206,Bamako.','slug' => 'bp-206bamako','country' => 'Mali','status' => '1','created_at' => '2024-06-20 09:54:19','updated_at' => '2024-06-20 09:54:19'),
            array('address' => 'Noumouso Kadiolo,Sikasso.','slug' => 'noumouso-kadiolosikasso','country' => 'Mali','status' => '1','created_at' => '2024-06-20 09:54:42','updated_at' => '2024-06-20 09:54:42'),
            array('address' => '2 NO. 2802, HIDALGO, 25096,Coahuila.','slug' => '2-no-2802-hidalgo-25096coahuila','country' => 'Mexico','status' => '1','created_at' => '2024-06-20 09:55:14','updated_at' => '2024-06-20 09:55:14'),
            array('address' => 'HERON PEREZ NO. 35 B, FERRER GUARDIA, 91020,Veracruz','slug' => 'heron-perez-no-35-b-ferrer-guardia-91020veracruz','country' => 'Mexico','status' => '1','created_at' => '2024-06-20 09:55:35','updated_at' => '2024-06-20 09:55:35'),
            array('address' => 'avenue Al Abtal ang. rue de Sebou, 10090,Rabat.','slug' => 'avenue-al-abtal-ang-rue-de-sebou-10090rabat','country' => 'Morocco','status' => '1','created_at' => '2024-06-20 09:56:23','updated_at' => '2024-06-20 09:56:23'),
            array('address' => '21, rue Agadir,Rue Agadir.','slug' => '21-rue-agadirrue-agadir','country' => 'Morocco','status' => '1','created_at' => '2024-06-20 09:56:55','updated_at' => '2024-06-20 09:56:55'),
            array('address' => 'P.O. Box 679 Avenue Agostinho Neto, No. 960 Maputo','slug' => 'po-box-679-avenue-agostinho-neto-no-960-maputo','country' => 'Mozambique','status' => '1','created_at' => '2024-06-20 09:57:42','updated_at' => '2024-06-20 09:57:42'),
            array('address' => 'Rua Consiglieri Pedroso no. 165 Box 1072 Maputo','slug' => 'rua-consiglieri-pedroso-no-165-box-1072-maputo','country' => 'Mozambique','status' => '1','created_at' => '2024-06-20 09:57:56','updated_at' => '2024-06-20 09:57:56'),
            array('address' => 'Unity Plaza Unit 312 2 Galle Road, 04,Colombo','slug' => 'unity-plaza-unit-312-2-galle-road-04colombo','country' => 'Sri Lanka','status' => '1','created_at' => '2024-06-20 09:58:22','updated_at' => '2024-06-20 09:58:22'),
            array('address' => '4-02 Level Colombo 04 Majestic City Station Road, 04,Colombo','slug' => '4-02-level-colombo-04-majestic-city-station-road-04colombo','country' => 'Sri Lanka','status' => '1','created_at' => '2024-06-20 09:58:54','updated_at' => '2024-06-20 09:58:54'),
            array('address' => '17th Floor Ppf Towers Ohio Street/Garden Avenue, P.O. Box: 78769,Dar Es Salaam','slug' => '17th-floor-ppf-towers-ohio-streetgarden-avenue-po-box-78769dar-es-salaam','country' => 'Tanzania','status' => '1','created_at' => '2024-06-20 09:59:26','updated_at' => '2024-06-20 09:59:26'),
            array('address' => 'P.o. Box 1859,Dar Es Salaam.','slug' => 'po-box-1859dar-es-salaam','country' => 'Tanzania','status' => '1','created_at' => '2024-06-20 09:59:49','updated_at' => '2024-06-20 09:59:49'),
            array('address' => 'AÇIKTAN S N 46/1., Siteler,Altındağ.','slug' => 'aciktan-s-n-461-siteleraltindag','country' => 'Turkey','status' => '1','created_at' => '2024-06-20 10:00:18','updated_at' => '2024-06-20 10:00:18'),
            array('address' => 'Yeni Sanayi, Altınpark Sk. No :1 16400 İNEGÖL,Bursa.','slug' => 'yeni-sanayi-altinpark-sk-no-1-16400-inegolbursa','country' => 'Turkey','status' => '1','created_at' => '2024-06-20 10:00:36','updated_at' => '2024-06-20 10:00:36'),
            array('address' => '99 Lutaya Close - Nantale Road Bukoto, 7458,Kampala','slug' => '99-lutaya-close-nantale-road-bukoto-7458kampala','country' => 'Uganda','status' => '1','created_at' => '2024-06-20 10:01:00','updated_at' => '2024-06-20 10:01:00'),
            array('address' => 'Plot9 3rd Street Behind Iran Exhibition, P.O.Box 26504','slug' => 'plot9-3rd-street-behind-iran-exhibition-pobox-26504','country' => 'Uganda','status' => '1','created_at' => '2024-06-20 10:01:09','updated_at' => '2024-06-20 10:01:09'),
            array('address' => '502 Phu Rieng Do Street, Tan Dong Ward,Dong Xoai Town.','slug' => '502-phu-rieng-do-street-tan-dong-warddong-xoai-town','country' => 'Vietnam','status' => '1','created_at' => '2024-06-20 10:01:31','updated_at' => '2024-06-20 10:01:31'),
            array('address' => '229/36B Thich Quang Duc, Ward 4,Phu Nhuan Dist.','slug' => '22936b-thich-quang-duc-ward-4phu-nhuan-dist','country' => 'Vietnam','status' => '1','created_at' => '2024-06-20 10:01:49','updated_at' => '2024-06-20 10:01:49'),
            array('address' => 'C3-07 Hamlet 3, An Phu President Area,An Phu President Area.','slug' => 'c3-07-hamlet-3-an-phu-president-areaan-phu-president-area','country' => 'Vietnam','status' => '1','created_at' => '2024-06-20 10:02:18','updated_at' => '2024-06-20 10:02:18'),
            array('address' => 'Plot 513 Kwacha Rd, P.O.Box 11230,Chingola.','slug' => 'plot-513-kwacha-rd-pobox-11230chingola','country' => 'Zambia','status' => '1','created_at' => '2024-06-20 10:02:50','updated_at' => '2024-06-20 10:02:50'),
            array('address' => 'Findeco Hse, Cairo Rd, P.O.Box 34471,Cairo Rd.','slug' => 'findeco-hse-cairo-rd-pobox-34471cairo-rd','country' => 'Zambia','status' => '1','created_at' => '2024-06-20 10:03:37','updated_at' => '2024-06-20 10:03:37'),
            array('address' => '103 B Chinhoyi Stree Kitchen Building,Harare.','slug' => '103-b-chinhoyi-stree-kitchen-buildingharare','country' => 'Zimbabwe','status' => '1','created_at' => '2024-06-20 10:04:05','updated_at' => '2024-06-20 10:04:05'),
            array('address' => '106 Coronation Ave,Harare.','slug' => '106-coronation-aveharare','country' => 'Zimbabwe','status' => '1','created_at' => '2024-06-20 10:04:20','updated_at' => '2024-06-20 10:04:20')
        );

        CashPickup::insert($cash_pickups);
    }
}
