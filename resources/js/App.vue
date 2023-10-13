<template>
  <div class="form-window">
    <div class="header">
      <div>Сформировать баланс</div>
      <hr style="border: 0.5px solid black" />
    </div>
    <div class="form">
      <div>По состоянию на</div>
      <div>
        <input v-model="this.date" type="date" placeholder="дд.мм.гггг" />
      </div>
      <div>Организация</div>
      <div>
        <input v-model="organization" list="dataList" class="selector" />
        <datalist id="dataList">
          <option v-for="organization in organizations" :value="organization.full_name">
            {{ organization.full_name }}<span v-if="organization.unp!=null">{{' УНП ' + organization.unp }}</span>
          </option>
        </datalist>
        <div class="error" v-if="error !=null">{{ error }}</div>
      </div>
    </div>
    <div class="buttons">
      <button @click="getSmetaForSelectDate()">Сформировать</button>
      <button @click="clear()">Отчистить</button>
    </div>
  </div>
  <div class="header-2-and-download">
    <div>Наличие лесоматериалов на балансе организации</div>
    <button @click="download()">Скачать отчет в Exel</button>
  </div>
  <hr style="border: 0.5px solid black" />
  <table >
    <thead>
      <tr>
        <th>Организация</th>
        <th>Порода древесины/Номенклатура</th>
        <th>Остаток на начало</th>
        <th>Поступило на период</th>
        <th>Переработка</th>
        <th>Остаток на конец</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="smeta in smetas">
        <td>{{ smeta.organization }}</td>
        <td>{{ smeta.wood }}</td>
        <td>{{ smeta.volume_wood_begin }}</td>
        <td>{{ smeta.volume_wood_prihod }}</td>
        <td>{{ smeta.volume_wood_prod }}</td>
        <td>
          {{
           Number(smeta.volume_wood_begin) + Number(smeta.volume_wood_prihod) - Number(smeta.volume_wood_prod)
          }}
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
export default {
  data() {
    return {
      date: null,
      organization: null,
      organizations: null,
      smetas: null,
      error: null,
    };
  },
  async mounted() {
    await this.getOrganizations();
    await this.getSmetaToday();
  },
  methods: {
    getOrganizations() {
      axios.get("/api/organizations").then((res) => {
        this.organizations = res.data;
      });
    },
    getSmetaToday() {
      axios.get("/api/smetatoday").then((res) => {
        this.smetas = res.data;
      });
    },
    clear() {
      axios.get("/api/smetatoday").then((res) => {
        this.smetas = res.data;
      });
      this.organization = null;
      this.date = null;
    },
    getSmetaForSelectDate() {
      axios
        .post("/api/smetaforselecteddate", {
          organization: this.organization,
          date: this.date,
        })
        .then((res) => {
          this.smetas = res.data;
          this.error = null;
        })

        .catch(error => {
            if(error.response.status==404){
                this.error=error.response.data.message;
            } else{
          this.error=error.response.data.message;
            }
        });
    },
    download(){
        axios.post('/api/download/smeta', 
        {
          organization: this.organization,
          date: this.date,
        })
        .then(res => {
          this.error = null;
          if(this.organization==null){
            this.organization='';
          }
          window.location.href = 'api/download/'+ this.date +'/' + this.organization;
          
;
        })
        .catch(error => {
            if(error.response.status==404){
                this.error=error.response.data.message;
            } else{
          this.error=error.response.data.message;
            }

    });
    },
  },
};
</script>
<style>
.form-window {
  height: 110px;
  background-color: rgb(226, 229, 231);
  box-shadow: 2px 2px 4px 1px;
}
.header {
  margin-left: 20px;
}
.form {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
  margin-left: 40px;
}
.selector {
  width: 800px;
}
.buttons {
  display: flex;
}
button {
  width: 150px;
  height: 30px;
  margin-left: 40px;
  border: none;
  color: aliceblue;
  border-radius: 7px;
  background: fixed;
  background-color: rgb(10, 170, 116);
}
.header-2-and-download {
  margin-top: 40px;
  display: flex;
  justify-content: space-between;
}
table {
  border-collapse: collapse;
  width: 100%;
}

table th, table td {
  border: 1px solid black;
  padding: 8px;
  text-align: left;
}

table th {
  background-color: gray;
  color: white;
}

/* Стилизация заголовков столбцов */
table th {
  font-weight: bold;
}

/* Пример стилей для ячеек */
table td {
  background-color: white;
  color: black;
}

/* Выравнивание текста */
table td {
  text-align: center;
}

/* Пример изменения ширины столбцов */
table th:nth-child(1),
table td:nth-child(1) {
  width: 20%;
}

table th:nth-child(2),
table td:nth-child(2) {
  width: 30%;
}

table th:nth-child(3),
table td:nth-child(3) {
  width: 50%;
}

.error{
    color:red;
}
</style>
