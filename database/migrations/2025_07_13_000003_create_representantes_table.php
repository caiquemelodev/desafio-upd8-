<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('representantes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf', 14)->unique();
            $table->string('telefone')->nullable();
            $table->unsignedBigInteger('cidade_id');
            $table->timestamps();

            $table->foreign('cidade_id')->references('id')->on('cidades');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('representantes');
    }
};
