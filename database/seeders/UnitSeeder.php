<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\AlamatUnit;
use App\Models\FasilitasTipeKamar;
use App\Models\TipeKamar;
use App\Models\HargaKamar;
use App\Models\Fasilitas;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Unit::factory()->count(5)->create();

        $ownerMap = [
            1 => '01976770-aad8-703f-9d5f-c5e9ecf5b63e',
            2 => '01976770-abd8-7190-88e2-c7c3a91f3b8b',
            3 => '01976770-acca-737e-b1fd-2c252ae5fa2d',
            4 => '01976770-adbc-73fc-ad26-9cbba14cb652',
            5 => '01976770-aeb2-733b-8151-d4210d5d988f',
            6 => '01976770-afa4-73f0-84d1-47c73a18cdf0',
            7 => '01976770-b09b-7144-b61c-050ac5d653cb',
            8 => '01976770-b192-72b3-a697-71439fbfdf58',
            9 => '01976770-b28b-721b-8044-cb6bb12d7e22',
            10 => '01976770-b38c-703a-8977-245c3e512c79',
            11 => '01976770-b47f-7094-9863-30eb1393c596',
            12 => '01976770-b56e-7116-b71e-eccf372e9ef2',
            13 => '01976770-b665-73a8-82e8-2ce0cdb43e4e',
            14 => '01976770-b765-73ae-9e92-1f5dbfa07cef',
            15 => '01976770-b861-703f-a92b-4ae3d5d98de8',
            16 => '01976770-b955-71fb-ae2f-9ffddb249e09',
            17 => '01976770-ba4b-71d6-a40f-dcc117c69fea',
            18 => '01976770-bb41-70c2-ba19-8532fba888d3',
            19 => '01976770-bc39-72df-af92-754b33a91622',
            20 => '01976770-bd31-7396-95bc-053b589a8c61',
            21 => '01976770-be25-7206-9c59-22837035c2a2',
            22 => '01976770-bf2e-73d0-9017-402a1f173ed3',
            23 => '01976770-c032-71d8-bd1f-5828c6635681',
            24 => '01976770-c133-7037-aec5-19d97386972a',
            25 => '01976770-c222-70c1-998c-ea4d5a8bf525',
            26 => '01976770-c314-71ab-8275-a94a65c9e3e6',
            27 => '01976770-c407-727e-98a5-9b6177525740',
            28 => '01976770-c4f5-7342-9c7e-012cce00adda',
            29 => '01976770-c5ec-7395-a2be-094f8e065db1',
            30 => '01976770-c6e5-7235-a87c-2028c062c8f2',
            31 => '01976770-c7dd-7399-81b5-1bd974d5d4ac',
            32 => '01976770-c8d7-70f0-9f4c-847a6309b394',
            33 => '01976770-c9f0-7118-830f-1ccf5898e0da',
            34 => '01976770-cae6-70b4-9e8f-ef0722254fa8',
            35 => '01976770-cbd9-72e5-9691-ec1b72db821d',
            36 => '01976770-ccdb-70fa-ae41-5fcb8c4fd845',
            37 => '01976770-cdd4-70c3-bc4b-0d1e495209e4',
            38 => '01976770-cec4-71e8-9215-86fa663cb531',
            39 => '01976770-cfba-7354-b176-63cc0e3924a8',
            40 => '01976770-d0b7-73d2-9421-6a4a9933deed',
            41 => '01976770-d1af-739a-9073-df1706692dc4',
            42 => '01976770-d2a3-70da-8849-aa78a8b7c6b4',
            43 => '01976770-d39d-711c-9c29-b90474497031',
            44 => '01976770-d4a0-71d4-8090-f5a44ad6ba20',
            45 => '01976770-d5a1-724a-a147-21391b95d353',
            46 => '01976770-d697-7237-b609-691588e8a467',
            47 => '01976770-d7a6-7075-bb99-0a03562457ba',
            48 => '01976770-d8b2-72d2-9e20-fabbb434b014',
            49 => '01976770-d9a8-7301-96f3-b0142cf7a895',
            50 => '01976770-da9f-7316-8879-31cab62a3a9d',
            51 => '01976770-db9d-72e6-aedd-84fb3db26778',
            52 => '01976770-dc9a-734c-a459-957757e6cae2',
            53 => '01976770-dd8f-701b-bcc3-8fcda210f431',
            54 => '01976770-de80-71ab-b6fc-de55f7a41643',
            55 => '01976770-df7d-72a9-83f4-3dee87d3d751',
            56 => '01976770-e085-716c-ab37-bd95048792ef',
            57 => '01976770-e185-73bc-91a0-68b0b347ad8d',
            58 => '01976770-e274-7157-94ba-e71a4c7b59b1',
            59 => '01976770-e36e-72a1-b0be-59697b57bceb',
            60 => '01976770-e466-708e-b93a-39b18acd93c6',
            61 => '01976770-e565-7353-b23d-629d0de2d431',
            62 => '01976770-e65d-7325-a22d-47f59b46a53f',
            63 => '01976770-e75b-717a-b5cf-4a5252d66f72',
            64 => '01976770-e857-70c9-97af-8df374c86b25',
            65 => '01976770-e950-7052-a33d-04b64ae4ca9a',
            66 => '01976770-ea41-73b7-9309-70d72a0ef404',
            67 => '01976770-eb34-734e-90db-03ec5aa2a88f',
            68 => '01976770-ec34-7383-8ed5-5bff081554c3',
            69 => '01976770-ed25-7373-af77-bb99159c6ec3',
            70 => '01976770-ee16-73fd-9195-a83526d5797d',
            71 => '01976770-ef10-7056-8898-89b8b0fb2d1b',
            72 => '01976770-f00d-72f5-a914-63948f117621',
            73 => '01976770-f104-7057-9cb9-f3345598c0fe',
            74 => '01976770-f200-70c0-932b-2ea5a2a49726',
            75 => '01976770-f2ec-72a6-9e81-973376c70acb',
            76 => '01976770-f3e9-728d-a458-e4cc871eebda',
            77 => '01976770-f4e0-7387-b887-d06649fa1832',
            78 => '01976770-f5cd-71a1-9a7c-2cace6b68402',
            79 => '01976770-f5cd-71a1-9a7c-2cace6b68402',
        ];

        $dataUnitsLama = [
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H2-9',
                'no_kontrak' => '001/KZK/03/2020',
                'tgl_awal' => '1/4/2020',
                'tgl_akhir' => '1/4/2022',
                'id_owner_lama' => 1,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 1-2',
                'no_kontrak' => '001/KZK/05/2020',
                'tgl_awal' => '1/6/2020',
                'tgl_akhir' => '1/6/2021',
                'id_owner_lama' => 2,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H2-10',
                'no_kontrak' => '002/KZK/05/2020',
                'tgl_awal' => '1/6/2020',
                'tgl_akhir' => '1/6/2022',
                'id_owner_lama' => 3,
                'active' => false,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H22-1',
                'no_kontrak' => '001/KZK/06/2020',
                'tgl_awal' => '10/6/2020',
                'tgl_akhir' => '10/6/2022',
                'id_owner_lama' => 4,
                'active' => false,
            ],
            [
                'alamat' => 'Regentown',
                'no_unit' => 'A5-11',
                'no_kontrak' => '002/KZK/06/2020',
                'tgl_awal' => '11/6/2020',
                'tgl_akhir' => '11/6/2022',
                'id_owner_lama' => 5,
                'active' => false,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H17-1',
                'no_kontrak' => '003/KZK/06/2020',
                'tgl_awal' => '22/6/2020',
                'tgl_akhir' => '22/6/2022',
                'id_owner_lama' => 6,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H17-19',
                'no_kontrak' => '002/KZK/07/2020',
                'tgl_awal' => '4/7/2020',
                'tgl_akhir' => '4/7/2022',
                'id_owner_lama' => 8,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H7-9',
                'no_kontrak' => '003/KZK/07/2020',
                'tgl_awal' => '1/7/2020',
                'tgl_akhir' => '1/8/2022',
                'id_owner_lama' => 9,
                'active' => true,
            ],
            [
                'alamat' => 'Piazza the Mozia',
                'no_unit' => 'F5-5',
                'no_kontrak' => '004/KZK/07/2020',
                'tgl_awal' => '1/7/2020',
                'tgl_akhir' => '1/8/2022',
                'id_owner_lama' => 10,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 1-9',
                'no_kontrak' => '002/KZK/08/2020',
                'tgl_awal' => '1/8/2020',
                'tgl_akhir' => '1/9/2022',
                'id_owner_lama' => 11,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H6-9',
                'no_kontrak' => '003/KZK/08/2020',
                'tgl_awal' => '1/8/2020',
                'tgl_akhir' => '1/9/2022',
                'id_owner_lama' => 12,
                'active' => false,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H1-8',
                'no_kontrak' => '-',
                'tgl_awal' => '1/9/2020',
                'tgl_akhir' => '1/9/2022',
                'id_owner_lama' => 13,
                'active' => false,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 6-5',
                'no_kontrak' => '001/KZK/09/2020',
                'tgl_awal' => '1/9/2020',
                'tgl_akhir' => '1/9/2022',
                'id_owner_lama' => 14,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H16-6',
                'no_kontrak' => '002/KZK/09/2020',
                'tgl_awal' => '15/9/2020',
                'tgl_akhir' => '15/9/2022',
                'id_owner_lama' => 15,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 1-1',
                'no_kontrak' => '003/KZK/09/2020',
                'tgl_awal' => '1/9/2020',
                'tgl_akhir' => '1/10/2022',
                'id_owner_lama' => 16,
                'active' => true,
            ],
            [
                'alamat' => 'Piazza the Mozia',
                'no_unit' => 'F3-5',
                'no_kontrak' => '001/KZK/10/2020',
                'tgl_awal' => '1/10/2020',
                'tgl_akhir' => '1/10/2022',
                'id_owner_lama' => 17,
                'active' => true,
            ],
            [
                'alamat' => 'Jl. Bendungan Hillir',
                'no_unit' => '17A',
                'no_kontrak' => '002/KZK/10/2020',
                'tgl_awal' => '1/10/2020',
                'tgl_akhir' => '1/11/2021',
                'id_owner_lama' => 18,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 6-2',
                'no_kontrak' => '002/KZK/10/2020',
                'tgl_awal' => '1/10/2020',
                'tgl_akhir' => '1/11/2022',
                'id_owner_lama' => 20,
                'active' => true,
            ],
            [
                'alamat' => 'Saveria Apartment',
                'no_unit' => 'NT12-31',
                'no_kontrak' => '002/KZK/11/2020',
                'tgl_awal' => '1/11/2020',
                'tgl_akhir' => '1/11/2021',
                'id_owner_lama' => 21,
                'active' => false,
            ],
            [
                'alamat' => 'Regentown',
                'no_unit' => 'J1-15',
                'no_kontrak' => '003/KZK/11/2020',
                'tgl_awal' => '13/11/2020',
                'tgl_akhir' => '13/11/2021',
                'id_owner_lama' => 22,
                'active' => false,
            ],
            [
                'alamat' => 'Regentown',
                'no_unit' => 'J1-16',
                'no_kontrak' => '003/KZK/11/2020',
                'tgl_awal' => '13/11/2020',
                'tgl_akhir' => '13/11/2021',
                'id_owner_lama' => 22,
                'active' => false,
            ],
            [
                'alamat' => 'Regentown',
                'no_unit' => 'J1-17',
                'no_kontrak' => '003/KZK/11/2020',
                'tgl_awal' => '13/11/2020',
                'tgl_akhir' => '13/11/2021',
                'id_owner_lama' => 22,
                'active' => false,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 1-3',
                'no_kontrak' => '004/KZK/11/2020',
                'tgl_awal' => '20/11/2020',
                'tgl_akhir' => '20/11/2022',
                'id_owner_lama' => 23,
                'active' => true,
            ],
            [
                'alamat' => 'Ingenia',
                'no_unit' => 'B3-11',
                'no_kontrak' => '-',
                'tgl_awal' => '20/2/2020',
                'tgl_akhir' => '20/2/2022',
                'id_owner_lama' => 5,
                'active' => false,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H20-1',
                'no_kontrak' => '001/KZK/07/2020',
                'tgl_awal' => '2/7/2020',
                'tgl_akhir' => '2/7/2022',
                'id_owner_lama' => 7,
                'active' => false,
            ],
            [
                'alamat' => 'U-Residence',
                'no_unit' => '3533',
                'no_kontrak' => '-',
                'tgl_awal' => '20/11/2020',
                'tgl_akhir' => '20/11/2022',
                'id_owner_lama' => 20,
                'active' => false,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H11-2',
                'no_kontrak' => '001/KZK/12/2020',
                'tgl_awal' => '5/12/2020',
                'tgl_akhir' => '5/12/2022',
                'id_owner_lama' => 24,
                'active' => false,
            ],
            [
                'alamat' => 'Piazza the Mozia',
                'no_unit' => 'F11-1',
                'no_kontrak' => '001/KZK/01/2021',
                'tgl_awal' => '7/1/2021',
                'tgl_akhir' => '7/1/2023',
                'id_owner_lama' => 25,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H7-2',
                'no_kontrak' => '002/KZK/01/2021',
                'tgl_awal' => '8/1/2021',
                'tgl_akhir' => '8/1/2023',
                'id_owner_lama' => 26,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 5-7',
                'no_kontrak' => '001/KZK/02/2021',
                'tgl_awal' => '5/2/2021',
                'tgl_akhir' => '5/2/2023',
                'id_owner_lama' => 27,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H3-16',
                'no_kontrak' => '002/KZK/04/2021',
                'tgl_awal' => '17/4/2021',
                'tgl_akhir' => '17/4/2023',
                'id_owner_lama' => 28,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H19-5',
                'no_kontrak' => '001/KZK/05/2021',
                'tgl_awal' => '20/5/2021',
                'tgl_akhir' => '20/5/2023',
                'id_owner_lama' => 29,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 6-7',
                'no_kontrak' => '002/KZK/05/2021',
                'tgl_awal' => '26/5/2021',
                'tgl_akhir' => '26/5/2023',
                'id_owner_lama' => 30,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H7-7',
                'no_kontrak' => '003/KZK/05/2021',
                'tgl_awal' => '30/5/2021',
                'tgl_akhir' => '30/5/2023',
                'id_owner_lama' => 32,
                'active' => true,
            ],
            [
                'alamat' => 'Allogio',
                'no_unit' => 'B6-73',
                'no_kontrak' => '004/KZK/05/2021',
                'tgl_awal' => '29/5/2021',
                'tgl_akhir' => '29/5/2022',
                'id_owner_lama' => 31,
                'active' => false,
            ],
            [
                'alamat' => 'Piazza the Mozia',
                'no_unit' => 'F10-2',
                'no_kontrak' => '001/KZK/06/2021',
                'tgl_awal' => '1/6/2021',
                'tgl_akhir' => '1/6/2023',
                'id_owner_lama' => 33,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H21-12',
                'no_kontrak' => '002/KZK/06/2021',
                'tgl_awal' => '17/6/2021',
                'tgl_akhir' => '17/6/2023',
                'id_owner_lama' => 34,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 6-2',
                'no_kontrak' => '001/KZK/07/2021',
                'tgl_awal' => '19/7/2021',
                'tgl_akhir' => '19/7/2023',
                'id_owner_lama' => 35,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 6-3',
                'no_kontrak' => '001/KZK/07/2021',
                'tgl_awal' => '19/7/2021',
                'tgl_akhir' => '19/7/2023',
                'id_owner_lama' => 35,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H16-10',
                'no_kontrak' => '001/KZK/08/2021',
                'tgl_awal' => '17/8/2021',
                'tgl_akhir' => '17/8/2023',
                'id_owner_lama' => 36,
                'active' => false,
            ],
            [
                'alamat' => 'Studento',
                'no_unit' => 'L16 no 19',
                'no_kontrak' => '002/KZK/10/2021',
                'tgl_awal' => '7/10/2021',
                'tgl_akhir' => '31/10/2022',
                'id_owner_lama' => 37,
                'active' => false,
            ],
            [
                'alamat' => 'Allogio',
                'no_unit' => 'T2-3',
                'no_kontrak' => '003/KZK/10/202',
                'tgl_awal' => '24/10/2021',
                'tgl_akhir' => '31/10/2022',
                'id_owner_lama' => 38,
                'active' => true,
            ],
            [
                'alamat' => 'Allogio',
                'no_unit' => 'B5-90',
                'no_kontrak' => '001/KZK/11/2021',
                'tgl_awal' => '3/11/2021',
                'tgl_akhir' => '31/10/2022',
                'id_owner_lama' => 39,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 6-3',
                'no_kontrak' => '002/KZK/11/2021',
                'tgl_awal' => '13/11/2021',
                'tgl_akhir' => '12/11/2023',
                'id_owner_lama' => 40,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H11-3',
                'no_kontrak' => '003/KZK/11/2021',
                'tgl_awal' => '29/11/2021',
                'tgl_akhir' => '30/11/2023',
                'id_owner_lama' => 41,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H8-10',
                'no_kontrak' => '001/KZK/12/2021',
                'tgl_awal' => '29/12/2021',
                'tgl_akhir' => '1/1/2024',
                'id_owner_lama' => 42,
                'active' => true,
            ],
            [
                'alamat' => 'Ruko Pascal Timur',
                'no_unit' => 'No 9',
                'no_kontrak' => '002/KZK/03/2022',
                'tgl_awal' => '15/3/2022',
                'tgl_akhir' => '1/4/2024',
                'id_owner_lama' => 43,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 2-7',
                'no_kontrak' => '001/KZK/03/2022',
                'tgl_awal' => '3/3/2022',
                'tgl_akhir' => '3/3/2024',
                'id_owner_lama' => 44,
                'active' => true,
            ],
            [
                'alamat' => 'Allogio',
                'no_unit' => 'Barat 6 dan 7',
                'no_kontrak' => '020/KZK/IV/2022',
                'tgl_awal' => '20/4/2022',
                'tgl_akhir' => '20/4/2023',
                'id_owner_lama' => 45,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H15-3',
                'no_kontrak' => '-',
                'tgl_awal' => '1/4/2022',
                'tgl_akhir' => '1/4/2024',
                'id_owner_lama' => 36,
                'active' => false,
            ],
            [
                'alamat' => 'Assati Apartment',
                'no_unit' => 'Agate Purple 310',
                'no_kontrak' => '001/KZK/05/2022',
                'tgl_awal' => '14/5/2022',
                'tgl_akhir' => '14/5/2024',
                'id_owner_lama' => 46,
                'active' => false,
            ],
            [
                'alamat' => 'Zena',
                'no_unit' => 'G9 No 6',
                'no_kontrak' => '001/KZK/04/2022',
                'tgl_awal' => '9/4/2022',
                'tgl_akhir' => '30/4/2022',
                'id_owner_lama' => 47,
                'active' => true,
            ],
            [
                'alamat' => 'Regentown',
                'no_unit' => 'B8-10',
                'no_kontrak' => '001/KZK/06/2022',
                'tgl_awal' => '10/6/2022',
                'tgl_akhir' => '30/6/2022',
                'id_owner_lama' => 48,
                'active' => true,
            ],
            [
                'alamat' => 'Zena',
                'no_unit' => 'G12 No 3',
                'no_kontrak' => '002/KZK/06/2022',
                'tgl_awal' => '12/6/2022',
                'tgl_akhir' => '30/6/2022',
                'id_owner_lama' => 49,
                'active' => true,
            ],
            [
                'alamat' => 'Zena',
                'no_unit' => 'M6 No 3',
                'no_kontrak' => '003/KZK/06/2022',
                'tgl_awal' => '30/6/2022',
                'tgl_akhir' => '1/7/2024',
                'id_owner_lama' => 50,
                'active' => false,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H5-9A',
                'no_kontrak' => '001/KZK/08/2022',
                'tgl_awal' => '24/8/2022',
                'tgl_akhir' => '1/9/2024',
                'id_owner_lama' => 51,
                'active' => true,
            ],
            [
                'alamat' => 'Jl. Salak barat',
                'no_unit' => 'No 11',
                'no_kontrak' => '-',
                'tgl_awal' => '1/8/2022',
                'tgl_akhir' => '1/8/2024',
                'id_owner_lama' => 25,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Red 2-12',
                'no_kontrak' => '002/KZK/09/2022',
                'tgl_awal' => '23/9/2022',
                'tgl_akhir' => '1/10/2024',
                'id_owner_lama' => 52,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Orange 5-5',
                'no_kontrak' => '001/KZK/11/2022',
                'tgl_awal' => '7/11/2022',
                'tgl_akhir' => '30/11/2024',
                'id_owner_lama' => 53,
                'active' => false,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Orange 5-3',
                'no_kontrak' => '001/KZK/12/2022',
                'tgl_awal' => '3/12/2022',
                'tgl_akhir' => '1/1/2025',
                'id_owner_lama' => 54,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H12-16',
                'no_kontrak' => '002/KZK/01/2023',
                'tgl_awal' => '7/1/2023',
                'tgl_akhir' => '31/1/2025',
                'id_owner_lama' => 55,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H10-7',
                'no_kontrak' => '001/KZK/01/2023',
                'tgl_awal' => '7/1/2023',
                'tgl_akhir' => '31/1/2025',
                'id_owner_lama' => 56,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H5-15',
                'no_kontrak' => '002/KZK/12/2022',
                'tgl_awal' => '22/12/2022',
                'tgl_akhir' => '1/1/2025',
                'id_owner_lama' => 57,
                'active' => false,
            ],
            [
                'alamat' => 'Studento',
                'no_unit' => 'L19-15-16',
                'no_kontrak' => '001/KZK/03/2023',
                'tgl_awal' => '2/3/2023',
                'tgl_akhir' => '1/3/2025',
                'id_owner_lama' => 58,
                'active' => true,
            ],
            [
                'alamat' => 'Taman Bromo',
                'no_unit' => 'Gunung TImur no 37',
                'no_kontrak' => '003/KZK/01/2023',
                'tgl_awal' => '31/1/2023',
                'tgl_akhir' => '31/1/2025',
                'id_owner_lama' => 59,
                'active' => true,
            ],
            [
                'alamat' => 'Zena',
                'no_unit' => 'M3 No 6',
                'no_kontrak' => '002/KZK/03/2023',
                'tgl_awal' => '20/3/2023',
                'tgl_akhir' => '1/4/2024',
                'id_owner_lama' => 60,
                'active' => false,
            ],
            [
                'alamat' => 'Jl Mandala Raya',
                'no_unit' => 'No 10',
                'no_kontrak' => '002/KZK/04/2023',
                'tgl_awal' => '10/4/2023',
                'tgl_akhir' => '10/4/2024',
                'id_owner_lama' => 61,
                'active' => false,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 2-5',
                'no_kontrak' => '001/KZK/05/2023',
                'tgl_awal' => '9/5/2023',
                'tgl_akhir' => '9/5/2025',
                'id_owner_lama' => 62,
                'active' => false,
            ],
            [
                'alamat' => 'Studento',
                'no_unit' => 'L18-12',
                'no_kontrak' => '001/KZK/04/2023',
                'tgl_awal' => '2/4/2023',
                'tgl_akhir' => '1/4/2025',
                'id_owner_lama' => 63,
                'active' => true,
            ],
            [
                'alamat' => 'Jl. Mangga Besar IX',
                'no_unit' => 'No 15',
                'no_kontrak' => '001/KZK/06/2023',
                'tgl_awal' => '20/6/2023',
                'tgl_akhir' => '20/6/2024',
                'id_owner_lama' => 64,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Red 3-8',
                'no_kontrak' => '001/KZK/07/2023',
                'tgl_awal' => '3/7/2023',
                'tgl_akhir' => '3/7/2025',
                'id_owner_lama' => 65,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Red 3-6',
                'no_kontrak' => '001/KZK/09/2023',
                'tgl_awal' => '21/9/2023',
                'tgl_akhir' => '1/10/2025',
                'id_owner_lama' => 66,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Orange 6-6',
                'no_kontrak' => '001/KZK/12/2023',
                'tgl_awal' => '31/12/2023',
                'tgl_akhir' => '31/12/2025',
                'id_owner_lama' => 67,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Orange 9-12',
                'no_kontrak' => '',
                'tgl_awal' => '31/12/2023',
                'tgl_akhir' => '31/12/2025',
                'id_owner_lama' => 68,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H16-12',
                'no_kontrak' => '002/KZK/02/2024',
                'tgl_awal' => '5/2/2024',
                'tgl_akhir' => '5/2/2026',
                'id_owner_lama' => 69,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H10-6',
                'no_kontrak' => '-',
                'tgl_awal' => '1/4/2024',
                'tgl_akhir' => '1/4/2026',
                'id_owner_lama' => 70,
                'active' => true,
            ],
            [
                'alamat' => 'Studento',
                'no_unit' => 'L22-6',
                'no_kontrak' => '001/KZK/06/2024',
                'tgl_awal' => '1/6/2024',
                'tgl_akhir' => '1/6/2026',
                'id_owner_lama' => 71,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Red 2-2',
                'no_kontrak' => '001/KZK/06/2024',
                'tgl_awal' => '15/6/2024',
                'tgl_akhir' => '30/6/2026',
                'id_owner_lama' => 72,
                'active' => true,
            ],
            [
                'alamat' => 'Naturale',
                'no_unit' => 'N5 no 21',
                'no_kontrak' => '001/KZK/07/2024',
                'tgl_awal' => '6/7/2024',
                'tgl_akhir' => '6/7/2026',
                'id_owner_lama' => 73,
                'active' => false,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H15-20',
                'no_kontrak' => '003/KZK/07/2024',
                'tgl_awal' => '29/7/2024',
                'tgl_akhir' => '1/8/2026',
                'id_owner_lama' => 74,
                'active' => true,
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H17-7',
                'no_kontrak' => '001/KZK/09/2024',
                'tgl_awal' => '7/9/2024',
                'tgl_akhir' => '7/9/2026',
                'id_owner_lama' => 75,
                'active' => true,
            ],
            [
                'alamat' => 'Anggrek loka',
                'no_unit' => 'Jl anggrek pandan 1 ',
                'no_kontrak' => '011/KZK/10/2024',
                'tgl_awal' => '20/10/2024',
                'tgl_akhir' => '30/4/2025',
                'id_owner_lama' => 76,
                'active' => false,
            ],
            [
                'alamat' => 'Piazza the Mozia',
                'no_unit' => 'F7-7',
                'no_kontrak' => '001/KZK/12/2024',
                'tgl_awal' => '1/12/2024',
                'tgl_akhir' => '1/12/2026',
                'id_owner_lama' => 77,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 7-7',
                'no_kontrak' => '002/KZK/02/2025',
                'tgl_awal' => '10/2/2025',
                'tgl_akhir' => '28/2/2027',
                'id_owner_lama' => 78,
                'active' => true,
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Purple 1 No 11',
                'no_kontrak' => '001/KZK/04/2025',
                'tgl_awal' => '5/4/2025',
                'tgl_akhir' => '30/4/2027',
                'id_owner_lama' => 79,
                'active' => true,
            ],
            [
                'alamat' => 'Jl kebun jeruk 13 ',
                'no_unit' => 'no 30a',
                'no_kontrak' => '002/KZK/04/2025',
                'tgl_awal' => '25/4/2025',
                'tgl_akhir' => '30/4/2027',
                'id_owner_lama' => 51,
                'active' => true,
            ],
        ];

        $alamatMapping = [
            'allogio barat'   => 'Medang, Kec. Pagedangan, Kabupaten Tangerang, Banten 15334',
            'allogio timur'   => 'Jl. Alloggio Timur 2 No.27, Medang, Kec. Pagedangan, Kabupaten Tangerang, Banten 15334',
            'anarta'          => 'Vanya Park, Jl. Boulevard Barat BSD City, Cijantra, Pagedangan, Tangerang Regency, Banten 15335',
            'alesha'          => 'Jl. Raya Pagedangan No.67, Pagedangan, Kec. Pagedangan, Kabupaten Tangerang, Banten 15339',
            'piazza'          => 'Jl. Lkr. Botanika Selatan, Lengkong Kulon, Kec. Pagedangan, Kabupaten Tangerang, Banten',
            'zena'            => 'PJ6G+3P9, Lengkong Kulon, Kec. Pagedangan, Kabupaten Tangerang, Banten 15331',
            'studento'        => 'Jl. Studento No.18, Pagedangan, Kec. Pagedangan, Kabupaten Tangerang, Banten 15339',
            'regentown'       => 'Regentown A3/A5, Pagedangan, Kec. Pagedangan, Kabupaten Tangerang, Banten 15339',
            'benhil'          => 'ruko paskal timur Scientia Garden - S, Jl. Scientia Square Barat 1 No.20, Medang, Kec. Pagedangan, Kabupaten Tangerang, Banten 15810',
            'tanjung duren'   => 'Jl. Salak Barat 8 No.11, RT.12/RW.5, Tj. Duren Utara, Kec. Grogol petamburan, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11470',
            'mangga besar'    => 'Jl. Kebon Jeruk XIII No.30A, RT.5/RW.4, Taman Sari, Kec. Taman Sari, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11150',
            'taman bromo'     => 'Jl. Gn. Tim. No.37, Bencongan, Kec. Klp. Dua, Kabupaten Tangerang, Banten 15810',
        ];

        $mappingKhusus = [
            'jl salak barat' => [
                'provinsi' => 'DKI Jakarta',
                'kabupaten' => 'Kota Jakarta Barat',
                'kecamatan' => 'Grogol Petamburan',
            ],
            'jl kebun jeruk' => [
                'provinsi' => 'DKI Jakarta',
                'kabupaten' => 'Kota Jakarta Barat',
                'kecamatan' => 'Taman Sari',
            ],
            'taman bromo' => [
                'provinsi' => 'Banten',
                'kabupaten' => 'Kabupaten Tangerang',
                'kecamatan' => 'Kelapa Dua',
            ],
        ];

        foreach ($dataUnitsLama as $data) {
            $ownerUuid = $ownerMap[$data['id_owner_lama']] ?? null;
            if (!$ownerUuid) {
                continue;
            }

            try {
                $tanggalAwal = Carbon::createFromFormat('j/n/Y', $data['tgl_awal'])->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggalAwal = now()->toDateString();
            }

            try {
                $tanggalAkhir = Carbon::createFromFormat('j/n/Y', $data['tgl_akhir'])->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggalAkhir = now()->addYear()->toDateString();
            }

            $unitId = Str::uuid();
            $namaCluster = $data['alamat'] . ' ' . $data['no_unit'];
            $namaClusterLower = strtolower($namaCluster);

            // Default lokasi
            $provinsi = 'Banten';
            $kabupaten = 'Kabupaten Tangerang';
            $kecamatan = 'Pagedangan';

            // Cek mapping khusus
            foreach ($mappingKhusus as $keyword => $lokasi) {
                if (str_contains($namaClusterLower, $keyword)) {
                    $provinsi = $lokasi['provinsi'];
                    $kabupaten = $lokasi['kabupaten'];
                    $kecamatan = $lokasi['kecamatan'];
                    break;
                }
            }

            // Cari alamat lengkap
            $alamatLengkap = $data['alamat']; // default
            foreach ($alamatMapping as $keyword => $alamat) {
                if (str_contains($namaClusterLower, $keyword)) {
                    $alamatLengkap = $alamat;
                    break;
                }
            }

            Unit::create([
                'id' => $unitId,
                'id_owner' => $ownerUuid,
                'nomor_kontrak' => $data['no_kontrak'],
                'tanggal_awal_kontrak' => $tanggalAwal,
                'tanggal_akhir_kontrak' => $tanggalAkhir,

                'nama_cluster' => $data['alamat'] . ' ' . $data['no_unit'],
                'multi_tipe' => true,
                'disewakan_untuk' => 'campur',
                'deskripsi' => 'Unit ' . $data['alamat'] . ' ' . $data['no_unit'],

                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'status' => $data['active'] ?? false,
            ]);

            // Simpan alamat_unit
            AlamatUnit::create([
                'unit_id' => $unitId,
                'alamat' => $alamatLengkap,
                'provinsi' => $provinsi,
                'kabupaten' => $kabupaten,
                'kecamatan' => $kecamatan,
                'deskripsi' => null,
            ]);
        }
    }
}
