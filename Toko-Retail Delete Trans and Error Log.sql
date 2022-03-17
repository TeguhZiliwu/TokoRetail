DELETE FROM tstock;
DELETE FROM ttransactiondet;
DELETE FROM ttransaction;

INSERT INTO tglobalsetting (settingid, settingvalue, remark, createdby, createddate) 
  SELECT * FROM (SELECT 'isPrintActive','false', 'Untuk mengaktifkan atau menonaktifkan fungsi cetak struk saat melakukan transaksi penjualan', 'Admin', CURRENT_TIMESTAMP) AS temp
WHERE NOT EXISTS 
  (SELECT settingid FROM tglobalsetting WHERE settingid='isPrintActive');