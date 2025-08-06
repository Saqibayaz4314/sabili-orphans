<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orphans', function (Blueprint $table) {

            // البيانات الاساسية
            $table->id();
            $table->enum('role' ,['registered','audited','waiting','certified','sponsored'])->default('registered');
            $table->text('waiting_reason')->nullable();
            $table->string('name');
            $table->string('id_number' , 9)->unique();
            $table->date('birth_date');
            $table->string('orphan_code')->unique();
            $table->string('address');
            $table->enum('gender' , ['ذكر' , 'أنثى']);
            $table->enum('health_status' , ['سليم' , 'مريض']);

            // بيانات الأب
            $table->string('deceased_name');
            $table->string('deceased_id_number' , 9);
            $table->date('death_deceased_date');
            $table->enum('cause_deceased_death',['شهيد' , 'وفاة طبيعية'])->nullable();
            $table->enum('father_work',['يعمل' , 'لا يعمل'])->nullable();
            $table->enum('nature_father_work',['موظف حكومي' , 'موظف وكالة' , 'عمل خاص'])->nullable();
            $table->string('nature_work')->nullable();

            // بيانات الأم
            $table->string('mother_name');
            $table->string('mother_id_number' , 9);
            $table->date('mother_birth_date')->nullable();
            $table->enum('mother_status' , ['على قيد الحياة- أرملة','على قيد الحياة- متزوجة زوج آخر','على قيد الحياة- مطلقة','شهيدة/ متوفية']);
            $table->enum('mother_work' , ['تعمل','لا تعمل'])->nullable();
            $table->enum('nature_mother_work' , ['موظف حكومي','موظف وكالة','عمل خاص'])->nullable();

            // بيانات الوكيل
            $table->string('guardian_name');
            $table->string('guardian_id_number' ,9);
            $table->enum('guardian_relation' , ['أم','أخ/ت','جد/ة','عم/ة','خال/ة','غير ذلك']);
            $table->string('guardian_anthor_relation')->nullable();

            // بيانات التواصل
            $table->string('phone');
            $table->string('phone1');
            $table->string('email')->nullable();

            // بيانات استلام الكفالة
            $table->enum('bank_name' , ['فلسطين','الاسلامي الفلسطيني','الاسلامي العربي','القدس','الاردن','القاهرة عمان']);
            $table->string('bank_account_owner');
            $table->string('bank_owner_id_number');
            $table->string('phone_number_linked_bank');
            $table->string('bank_account_number');
            $table->string('wallet_owner')->nullable();
            $table->string('wallet_owner_id_number')->nullable();
            $table->string('owner_phone_linked_wallet')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orphans');
    }
};
