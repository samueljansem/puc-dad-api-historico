CREATE TABLE HistoricoEscolar (
estudante INTEGER UNSIGNED  NOT NULL  ,
tipo_ensino VARCHAR(60)  NULL  ,
ano_desde_ensino_medio INTEGER UNSIGNED  NULL  ,
fez_cursinho INTEGER UNSIGNED  NULL  ,
possui_bolsa INTEGER UNSIGNED  NULL    ,
INDEX HistoricoEscolar_FKIndex1(estudante));