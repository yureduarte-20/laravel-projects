<?php

namespace Database\Seeders;

use App\Models\Especialidade;
use Illuminate\Database\Seeder;

class EspecialidadesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidades = [
            [
                'especialidade' => 'Clínica Geral',
                'descricao' => 'Exames preventivos, diagnósticos, raspagem e profilaxia. Encaminhamento para especialistas.',
                'tempo_medio_consulta' => 30,
            ],
            [
                'especialidade' => 'Odontologia Estética',
                'descricao' => 'Clareamento dental, facetas de porcelana, lentes de contato dental, botox e preenchimento facial.',
                'tempo_medio_consulta' => 30,
                'tempo_medio_consulta_maximo' => 60,
            ],
            [
                'especialidade' => 'Periodontia',
                'descricao' => 'Doenças da gengiva e periodonto. Raspagem e profilaxia periodontal, tratamento de gengivite e periodontite, implantes dentários, enxertos ósseos.',
                'tempo_medio_consulta' => 45,
                'tempo_medio_consulta_maximo' => 60,
            ],
            [
                'especialidade' => 'Ortodontia',
                'descricao' => 'Correção da posição dos dentes e dos maxilares. Aparelhos fixos, móveis, invisíveis.',
                'tempo_medio_consulta' => 30,
                'tempo_medio_consulta_retorno' => 4,
            ],
            [
                'especialidade' => 'Odontopediatria',
                'descricao' => 'Saúde bucal de crianças desde o nascimento até a adolescência. Consultas preventivas, tratamentos de cáries, aplicação de flúor, orientações.',
                'tempo_medio_consulta' => 20,
            ],
            [
                'especialidade' => 'Endodontia',
                'descricao' => 'Tratamento de canal. Remoção da polpa dental infectada e preenchimento dos canais radiculares.',
                'tempo_medio_consulta' => 60,
                'tempo_medio_consulta_maximo' => 120,
            ],
            [
                'especialidade' => 'Implantodontia',
                'descricao' => 'Implantes dentários para substituir raízes dos dentes perdidos. Coroas, pontes e próteses totais.',
                'tempo_medio_consulta' => 30,
                'tempo_medio_consulta_maximo' => 60,
            ],
            [
                'especialidade' => 'Cirurgia Bucal e Maxilofacial',
                'descricao' => 'Cirurgias complexas na boca, maxila e mandíbula. Extração de dentes inclusos, correção de deformidades faciais, cirurgias de tumores, implantes maxilares.',
                'tempo_medio_consulta' => 30,
                'tempo_medio_consulta_maximo' => 60,
            ],
            [
                'especialidade' => 'Radiologia Odontológica',
                'descricao' => 'Exames radiográficos dos dentes, maxilares e ossos da face. Diagnóstico de doenças e planejamento de tratamentos.',
                'tempo_medio_consulta' => 15,
                'tempo_medio_consulta_maximo' => 30,
            ],
            [
                'especialidade' => 'Odontogeriatria',
                'descricao' => 'Saúde bucal de idosos. Consultas preventivas, tratamentos de cáries, periodontite, próteses dentárias, orientações.',
                'tempo_medio_consulta' => 30,
            ],
        ];
        $json = collect($especialidades);

        $json->each(function ($item) {
            Especialidade::firstOrCreate([
                'nome' => $item['especialidade'],
                'descricao' => $item['descricao'],
                'tempo_medio_consulta_minutos' => $item['tempo_medio_consulta'],
            ]);
        });
    }
}
