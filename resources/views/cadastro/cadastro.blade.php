@extends('layout.layout')
@section('content')
<h2 class="text-center">Concurso Público para Desenvolvedor de Software</h2>
<h4 class="text-center">Inscrição de candidato</h4>
<el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px" class="demo-ruleForm">
    <el-form-item label="Nome" prop="nome">
        <el-input :disabled="!saveButton" v-model="ruleForm.nome"></el-input>
    </el-form-item>
    <el-form-item label="CPF" prop="cpf">
        <el-input :disabled="!saveButton" type="text" v-mask="'###.###.###-##'" v-model="ruleForm.cpf"></el-input>
    </el-form-item>
    <el-form-item label="Endereço" prop="endereco">
        <el-input :disabled="!saveButton" v-model="ruleForm.endereco"></el-input>
    </el-form-item>
    <el-form-item label="Estado" prop="estado_id">
        <el-select :disabled="!saveButton" @change="getCidades" v-model="ruleForm.estado_id" placeholder="Selecione um estado">
            <el-option v-for="item in estados" :key="item.estado_id" :label="`${item.nome} (${item.sigla})`" :value="item.estado_id" />
        </el-select>
    </el-form-item>
    <el-form-item label="Cidade" prop="cidade_id">
        <el-select :disabled="!saveButton" v-model="ruleForm.cidade_id" no-data-text="Selecione um estado" placeholder="Selecione uma cidade">
            <el-option v-for="item in cidades" :key="item.cidade_id" :label="item.nome" :value="item.cidade_id" />
        </el-select>
    </el-form-item>
    <el-form-item label="Cargo" prop="cargo">
        <el-input :disabled="!saveButton" v-model="ruleForm.cargo"></el-input>
    </el-form-item>
    <el-form-item>
        <el-button v-show="clearFormButton" @click="resetForm('ruleForm')">Limpar formulário</el-button>
        <el-button id="printPageButton" v-show="subscriptionButton" type="success" @click="printPage">Imprimir comprovante</el-button>
        <el-button v-show="saveButton" type="primary" @click="submitForm('ruleForm')">Salvar inscrição</el-button>
    </el-form-item>
</el-form>
@endsection
@push('script')
<script>
    new Vue({
        el: '#app',
        data() {
            var checkName = (rule, value, callback) => {
                if (!/^[a-zA-ZÀ-ÖØ-öø-ÿ' ]{2,}$/g.test(value)) {
                    return callback(new Error('Nome completo inválido. Insira apenas letras com/sem apóstrofo'));
                }

                callback();
            };

            return {
                ruleForm: {
                    nome: '',
                    cpf: '',
                    endereco: '',
                    estado_id: '',
                    cidade_id: '',
                    cargo: '',
                },
                estados: [],
                cidades: [],
                saveButton: true,
                clearFormButton: true,
                subscriptionButton: false,
                rules: {
                    nome: [{
                            trigger: 'blur',
                            validator: checkName,
                        },
                        {
                            required: true,
                            message: 'Por favor, insira seu nome completo.',
                        },
                    ],
                    cpf: [{
                            required: true,
                            message: 'Por favor, insira seu CPF',
                        },
                        {
                            min: 14,
                            max: 14,
                            message: "Comprimento deve ser igual a 14 caracteres",
                        }
                    ],
                    endereco: [{
                        required: true,
                        message: 'Por favor, insira seu endereço',
                    }],
                    estado_id: [{
                        required: true,
                        message: 'Por favor, selecione um estado',
                    }],
                    cidade_id: [{
                        required: true,
                        message: 'Por favor, selecione uma cidade',
                    }],
                    cargo: [{
                        required: true,
                        message: 'Por favor, selecione um cargo',
                    }],
                }
            };
        },
        mounted() {
            this.getEstados();
        },
        methods: {
            submitForm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        axios.post("{{ route('pessoa_fisica.store') }}", this.ruleForm)
                            .then(res => {
                                pessoa = res.data;

                                axios.post("{{ route('inscricao.store') }} ", {
                                        'pessoa_fisica_id': pessoa.id,
                                        'situacao': 'enviado',
                                        'cargo': this.ruleForm.cargo
                                    }).then(resInscricao => {
                                        this.showMessage('success', 'Ok!', 'Inscrição realizada!')

                                        this.saveButton = false
                                        this.clearFormButton = false
                                        this.subscriptionButton = true
                                    })
                                    .catch(err => {
                                        this.showMessage('error', 'Opa...', 'Ocorreu um erro inesperado.')
                                        console.log(err)
                                    });
                            })
                            .catch(err => {
                                this.showMessage('error', 'Opa...', 'Ocorreu um erro inesperado.')
                                console.log(err)
                            });
                    } else {
                        this.showMessage('error', 'Opa...', 'Algum campo está inválido.')
                        return false;
                    }
                });
            },

            showMessage(type, title, message) {
                switch (type) {
                    case 'error':
                        iziToast.error({
                            title: title,
                            position: 'topRight',
                            message: message,
                            timeout: 54000,
                        });
                        break;
                    case 'success':
                        iziToast.success({
                            title: title,
                            position: 'topRight',
                            message: message,
                            timeout: 54000,
                        });
                        break;
                    default:
                        iziToast.info({
                            title: title,
                            position: 'topRight',
                            message: message,
                            timeout: 54000,
                        });
                        break;
                }
            },

            printPage() {
                let toast = document.querySelector('.iziToast');
                iziToast.hide({}, toast);
                
                window.print();
            },

            getEstados() {
                axios.get("{{ route('estados.index') }}")
                    .then(res => {
                        this.estados = res.data;
                    })
            },

            getCidades() {
                this.ruleForm.cidade_id = null;

                const uri = "{{ route('cidades.index') }}";

                axios.get(`${uri}/${this.ruleForm.estado_id}`)
                    .then(res => {
                        this.cidades = res.data;
                    })
            },

            resetForm(formName) {
                this.$refs[formName].resetFields();
            }
        },
    });
</script>
@endpush