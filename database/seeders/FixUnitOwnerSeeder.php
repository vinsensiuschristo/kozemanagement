<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class FixUnitOwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitMappings = [
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H2-9',
                'no_kontrak' => '001/KZK/03/2020',
                'id_owner_baru' => '01976770-aad8-703f-9d5f-c5e9ecf5b63e',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 1-2',
                'no_kontrak' => '001/KZK/05/2020',
                'id_owner_baru' => '01976770-abd8-7190-88e2-c7c3a91f3b8b',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H2-10',
                'no_kontrak' => '002/KZK/05/2020',
                'id_owner_baru' => '01976770-acca-737e-b1fd-2c252ae5fa2d',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H22-1',
                'no_kontrak' => '001/KZK/06/2020',
                'id_owner_baru' => '01976770-adbc-73fc-ad26-9cbba14cb652',
            ],
            [
                'alamat' => 'Regentown',
                'no_unit' => 'A5-11',
                'no_kontrak' => '002/KZK/06/2020',
                'id_owner_baru' => '01976770-aeb2-733b-8151-d4210d5d988f',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H17-1',
                'no_kontrak' => '003/KZK/06/2020',
                'id_owner_baru' => '01976770-afa4-73f0-84d1-47c73a18cdf0',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H17-19',
                'no_kontrak' => '002/KZK/07/2020',
                'id_owner_baru' => '01976770-b192-72b3-a697-71439fbfdf58',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H7-9',
                'no_kontrak' => '003/KZK/07/2020',
                'id_owner_baru' => '01976770-b28b-721b-8044-cb6bb12d7e22',
            ],
            [
                'alamat' => 'Piazza the Mozia',
                'no_unit' => 'F5-5',
                'no_kontrak' => '004/KZK/07/2020',
                'id_owner_baru' => '01976770-b38c-703a-8977-245c3e512c79',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 1-9',
                'no_kontrak' => '002/KZK/08/2020',
                'id_owner_baru' => '01976770-b47f-7094-9863-30eb1393c596',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H6-9',
                'no_kontrak' => '003/KZK/08/2020',
                'id_owner_baru' => '01976770-b56e-7116-b71e-eccf372e9ef2',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H1-8',
                'no_kontrak' => '-',
                'id_owner_baru' => '01976770-b665-73a8-82e8-2ce0cdb43e4e',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 6-5',
                'no_kontrak' => '001/KZK/09/2020',
                'id_owner_baru' => '01976770-b765-73ae-9e92-1f5dbfa07cef',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H16-6',
                'no_kontrak' => '002/KZK/09/2020',
                'id_owner_baru' => '01976770-b861-703f-a92b-4ae3d5d98de8',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 1-1',
                'no_kontrak' => '003/KZK/09/2020',
                'id_owner_baru' => '01976770-b955-71fb-ae2f-9ffddb249e09',
            ],
            [
                'alamat' => 'Piazza the Mozia',
                'no_unit' => 'F3-5',
                'no_kontrak' => '001/KZK/10/2020',
                'id_owner_baru' => '01976770-ba4b-71d6-a40f-dcc117c69fea',
            ],
            [
                'alamat' => 'Bendungan Hilir',
                'no_unit' => '17A',
                'no_kontrak' => '002/KZK/10/2020',
                'id_owner_baru' => '01976770-bb41-70c2-ba19-8532fba888d3',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 6-2',
                'no_kontrak' => '002/KZK/10/2020',
                'id_owner_baru' => '01976770-bc39-72df-af92-754b33a91622',
            ],
            [
                'alamat' => 'Saveria Apartment',
                'no_unit' => 'NT12-31',
                'no_kontrak' => '002/KZK/11/2020',
                'id_owner_baru' => '01976770-bd31-7396-95bc-053b589a8c61',
            ],
            [
                'alamat' => 'Regentown',
                'no_unit' => 'J1-15',
                'no_kontrak' => '003/KZK/11/2020',
                'id_owner_baru' => '01976770-be25-7206-9c59-22837035c2a2',
            ],
            [
                'alamat' => 'Regentown',
                'no_unit' => 'J1-16',
                'no_kontrak' => '003/KZK/11/2020',
                'id_owner_baru' => '01976770-be25-7206-9c59-22837035c2a2',
            ],
            [
                'alamat' => 'Regentown',
                'no_unit' => 'J1-17',
                'no_kontrak' => '003/KZK/11/2020',
                'id_owner_baru' => '01976770-be25-7206-9c59-22837035c2a2',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 1-3',
                'no_kontrak' => '004/KZK/11/2020',
                'id_owner_baru' => '01976770-bf2e-73d0-9017-402a1f173ed3',
            ],
            [
                'alamat' => 'Ingenia',
                'no_unit' => 'B3-11',
                'no_kontrak' => '-',
                'id_owner_baru' => '01976770-aeb2-733b-8151-d4210d5d988f',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H20-1',
                'no_kontrak' => '001/KZK/07/2020',
                'id_owner_baru' => '01976770-b09b-7144-b61c-050ac5d653cb',
            ],
            [
                'alamat' => 'U-Residence',
                'no_unit' => '3533',
                'no_kontrak' => '-',
                'id_owner_baru' => '01976770-bc39-72df-af92-754b33a91622',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H11-2',
                'no_kontrak' => '001/KZK/12/2020',
                'id_owner_baru' => '01976770-c032-71d8-bd1f-5828c6635681',
            ],
            [
                'alamat' => 'Piazza the Mozia',
                'no_unit' => 'F11-1',
                'no_kontrak' => '001/KZK/01/2021',
                'id_owner_baru' => '01976770-c133-7037-aec5-19d97386972a',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H7-2',
                'no_kontrak' => '002/KZK/01/2021',
                'id_owner_baru' => '01976770-c222-70c1-998c-ea4d5a8bf525',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 5-7',
                'no_kontrak' => '001/KZK/02/2021',
                'id_owner_baru' => '01976770-c314-71ab-8275-a94a65c9e3e6',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H3-16',
                'no_kontrak' => '002/KZK/04/2021',
                'id_owner_baru' => '01976770-c407-727e-98a5-9b6177525740',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H19-5',
                'no_kontrak' => '001/KZK/05/2021',
                'id_owner_baru' => '01976770-c4f5-7342-9c7e-012cce00adda',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 6-7',
                'no_kontrak' => '002/KZK/05/2021',
                'id_owner_baru' => '01976770-c5ec-7395-a2be-094f8e065db1',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H7-7',
                'no_kontrak' => '003/KZK/05/2021',
                'id_owner_baru' => '01976770-c7dd-7399-81b5-1bd974d5d4ac',
            ],
            [
                'alamat' => 'Allogio',
                'no_unit' => 'B6-73',
                'no_kontrak' => '004/KZK/05/2021',
                'id_owner_baru' => '01976770-c6e5-7235-a87c-2028c062c8f2',
            ],
            [
                'alamat' => 'Piazza the Mozia',
                'no_unit' => 'F10-2',
                'no_kontrak' => '001/KZK/06/2021',
                'id_owner_baru' => '01976770-c8d7-70f0-9f4c-847a6309b394',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H21-12',
                'no_kontrak' => '002/KZK/06/2021',
                'id_owner_baru' => '01976770-c9f0-7118-830f-1ccf5898e0da',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 6-2',
                'no_kontrak' => '001/KZK/07/2021',
                'id_owner_baru' => '01976770-cae6-70b4-9e8f-ef0722254fa8',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 6-3',
                'no_kontrak' => '001/KZK/07/2021',
                'id_owner_baru' => '01976770-cae6-70b4-9e8f-ef0722254fa8',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H16-10',
                'no_kontrak' => '001/KZK/08/2021',
                'id_owner_baru' => '01976770-cbd9-72e5-9691-ec1b72db821d',
            ],
            [
                'alamat' => 'Studento',
                'no_unit' => 'L16 no 19',
                'no_kontrak' => '002/KZK/10/2021',
                'id_owner_baru' => '01976770-ccdb-70fa-ae41-5fcb8c4fd845',
            ],
            [
                'alamat' => 'Allogio',
                'no_unit' => 'T2-3',
                'no_kontrak' => '003/KZK/10/202',
                'id_owner_baru' => '01976770-cdd4-70c3-bc4b-0d1e495209e4',
            ],
            [
                'alamat' => 'Allogio',
                'no_unit' => 'B5-90',
                'no_kontrak' => '001/KZK/11/2021',
                'id_owner_baru' => '01976770-cec4-71e8-9215-86fa663cb531',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 6-3',
                'no_kontrak' => '002/KZK/11/2021',
                'id_owner_baru' => '01976770-cfba-7354-b176-63cc0e3924a8',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H11-3',
                'no_kontrak' => '003/KZK/11/2021',
                'id_owner_baru' => '01976770-d0b7-73d2-9421-6a4a9933deed',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H8-10',
                'no_kontrak' => '001/KZK/12/2021',
                'id_owner_baru' => '01976770-d1af-739a-9073-df1706692dc4',
            ],
            [
                'alamat' => 'Ruko Pascal Timur',
                'no_unit' => 'No 9',
                'no_kontrak' => '002/KZK/03/2022',
                'id_owner_baru' => '01976770-d2a3-70da-8849-aa78a8b7c6b4',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Blue 2-7',
                'no_kontrak' => '001/KZK/03/2022',
                'id_owner_baru' => '01976770-d39d-711c-9c29-b90474497031',
            ],
            [
                'alamat' => 'Allogio',
                'no_unit' => 'Barat 6 dan 7',
                'no_kontrak' => '020/KZK/IV/2022',
                'id_owner_baru' => '01976770-d4a0-71d4-8090-f5a44ad6ba20',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H15-3',
                'no_kontrak' => '-',
                'id_owner_baru' => '01976770-cbd9-72e5-9691-ec1b72db821d',
            ],
            [
                'alamat' => 'Assati Apartment',
                'no_unit' => 'Agate Purple 310',
                'no_kontrak' => '001/KZK/05/2022',
                'id_owner_baru' => '01976770-d5a1-724a-a147-21391b95d353',
            ],
            [
                'alamat' => 'Zena',
                'no_unit' => 'G9 No 6',
                'no_kontrak' => '001/KZK/04/2022',
                'id_owner_baru' => '01976770-d697-7237-b609-691588e8a467',
            ],
            [
                'alamat' => 'Regentown',
                'no_unit' => 'B8-10',
                'no_kontrak' => '001/KZK/06/2022',
                'id_owner_baru' => '01976770-d7a6-7075-bb99-0a03562457ba',
            ],
            [
                'alamat' => 'Zena',
                'no_unit' => 'G12 No 3',
                'no_kontrak' => '002/KZK/06/2022',
                'id_owner_baru' => '01976770-d8b2-72d2-9e20-fabbb434b014',
            ],
            [
                'alamat' => 'Zena',
                'no_unit' => 'M6 No 3',
                'no_kontrak' => '003/KZK/06/2022',
                'id_owner_baru' => '01976770-d9a8-7301-96f3-b0142cf7a895',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H5-9A',
                'no_kontrak' => '001/KZK/08/2022',
                'id_owner_baru' => '01976770-da9f-7316-8879-31cab62a3a9d',
            ],
            [
                'alamat' => 'Jl. Salak barat',
                'no_unit' => 'No 11',
                'no_kontrak' => '-',
                'id_owner_baru' => '01976770-c133-7037-aec5-19d97386972a',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Red 2-12',
                'no_kontrak' => '002/KZK/09/2022',
                'id_owner_baru' => '01976770-db9d-72e6-aedd-84fb3db26778',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Orange 5-5',
                'no_kontrak' => '001/KZK/11/2022',
                'id_owner_baru' => '01976770-dc9a-734c-a459-957757e6cae2',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Orange 5-3',
                'no_kontrak' => '001/KZK/12/2022',
                'id_owner_baru' => '01976770-dd8f-701b-bcc3-8fcda210f431',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H12-16',
                'no_kontrak' => '002/KZK/01/2023',
                'id_owner_baru' => '01976770-de80-71ab-b6fc-de55f7a41643',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H10-7',
                'no_kontrak' => '001/KZK/01/2023',
                'id_owner_baru' => '01976770-df7d-72a9-83f4-3dee87d3d751',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H5-15',
                'no_kontrak' => '002/KZK/12/2022',
                'id_owner_baru' => '01976770-e085-716c-ab37-bd95048792ef',
            ],
            [
                'alamat' => 'Studento',
                'no_unit' => 'L19-15-16',
                'no_kontrak' => '001/KZK/03/2023',
                'id_owner_baru' => '01976770-e185-73bc-91a0-68b0b347ad8d',
            ],
            [
                'alamat' => 'Taman Bromo',
                'no_unit' => 'Gunung TImur no 37',
                'no_kontrak' => '003/KZK/01/2023',
                'id_owner_baru' => '01976770-e274-7157-94ba-e71a4c7b59b1',
            ],
            [
                'alamat' => 'Zena',
                'no_unit' => 'M3 No 6',
                'no_kontrak' => '002/KZK/03/2023',
                'id_owner_baru' => '01976770-e36e-72a1-b0be-59697b57bceb',
            ],
            [
                'alamat' => 'Jl Mandala Raya',
                'no_unit' => 'No 10',
                'no_kontrak' => '002/KZK/04/2023',
                'id_owner_baru' => '01976770-e466-708e-b93a-39b18acd93c6',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 2-5',
                'no_kontrak' => '001/KZK/05/2023',
                'id_owner_baru' => '01976770-e565-7353-b23d-629d0de2d431',
            ],
            [
                'alamat' => 'Studento',
                'no_unit' => 'L18-12',
                'no_kontrak' => '001/KZK/04/2023',
                'id_owner_baru' => '01976770-e65d-7325-a22d-47f59b46a53f',
            ],
            [
                'alamat' => 'Jl. Mangga Besar IX',
                'no_unit' => 'No 15',
                'no_kontrak' => '001/KZK/06/2023',
                'id_owner_baru' => '01976770-e75b-717a-b5cf-4a5252d66f72',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Red 3-8',
                'no_kontrak' => '001/KZK/07/2023',
                'id_owner_baru' => '01976770-e857-70c9-97af-8df374c86b25',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Red 3-6',
                'no_kontrak' => '001/KZK/09/2023',
                'id_owner_baru' => '01976770-e950-7052-a33d-04b64ae4ca9a',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Orange 6-6',
                'no_kontrak' => '001/KZK/12/2023',
                'id_owner_baru' => '01976770-ea41-73b7-9309-70d72a0ef404',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Orange 9-12',
                'no_kontrak' => 'nan',
                'id_owner_baru' => '01976770-eb34-734e-90db-03ec5aa2a88f',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H16-12',
                'no_kontrak' => '002/KZK/02/2024',
                'id_owner_baru' => '01976770-ec34-7383-8ed5-5bff081554c3',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H10-6',
                'no_kontrak' => '-',
                'id_owner_baru' => '01976770-ed25-7373-af77-bb99159c6ec3',
            ],
            [
                'alamat' => 'Studento',
                'no_unit' => 'L22-6',
                'no_kontrak' => '001/KZK/06/2024',
                'id_owner_baru' => '01976770-ee16-73fd-9195-a83526d5797d',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Red 2-2',
                'no_kontrak' => '001/KZK/06/2024',
                'id_owner_baru' => '01976770-ef10-7056-8898-89b8b0fb2d1b',
            ],
            [
                'alamat' => 'Naturale',
                'no_unit' => 'N5 no 21',
                'no_kontrak' => '001/KZK/07/2024',
                'id_owner_baru' => '01976770-f00d-72f5-a914-63948f117621',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H15-20',
                'no_kontrak' => '003/KZK/07/2024',
                'id_owner_baru' => '01976770-f104-7057-9cb9-f3345598c0fe',
            ],
            [
                'alamat' => 'Anarta House',
                'no_unit' => 'H17-7',
                'no_kontrak' => '001/KZK/09/2024',
                'id_owner_baru' => '01976770-f200-70c0-932b-2ea5a2a49726',
            ],
            [
                'alamat' => 'Anggrek loka',
                'no_unit' => 'Jl anggrek pandan 1 ',
                'no_kontrak' => '011/KZK/10/2024',
                'id_owner_baru' => '01976770-f2ec-72a6-9e81-973376c70acb',
            ],
            [
                'alamat' => 'Piazza the Mozia',
                'no_unit' => 'F7-7',
                'no_kontrak' => '001/KZK/12/2024',
                'id_owner_baru' => '01976770-f3e9-728d-a458-e4cc871eebda',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Yellow 7-7',
                'no_kontrak' => '002/KZK/02/2025',
                'id_owner_baru' => '01976770-f4e0-7387-b887-d06649fa1832',
            ],
            [
                'alamat' => 'Alesha House',
                'no_unit' => 'Purple 1 No 11',
                'no_kontrak' => '001/KZK/04/2025',
                'id_owner_baru' => '01976770-f5cd-71a1-9a7c-2cace6b68402',
            ],
            [
                'alamat' => 'Jl kebun jeruk 13 ',
                'no_unit' => 'no 30a',
                'no_kontrak' => '002/KZK/04/2025',
                'id_owner_baru' => '01976770-da9f-7316-8879-31cab62a3a9d',
            ],
        ];

        foreach ($unitMappings as $mapping) {
            $namaCluster = trim($mapping['alamat'] . ' ' . $mapping['no_unit']);

            $unit = Unit::where('nomor_kontrak', $mapping['no_kontrak'])
                ->where('nama_cluster', $namaCluster)
                ->first();

            if (!$unit) {
                $this->command->warn("❌ Unit {$namaCluster} dengan kontrak {$mapping['no_kontrak']} tidak ditemukan.");
                continue;
            }

            $unit->id_owner = $mapping['id_owner_baru'];
            $unit->save();

            $this->command->info("✅ Unit {$namaCluster} diupdate ke owner ID {$mapping['id_owner_baru']}");
        }
    }
}
