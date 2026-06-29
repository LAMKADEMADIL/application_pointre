import React, { useState, useEffect } from 'react';
import { StyleSheet, Text, View, FlatList, TouchableOpacity, SafeAreaView, Platform, ActivityIndicator } from 'react-native';

export default function App() {
  const [workers, setWorkers] = useState([]);
  const [loading, setLoading] = useState(true);

  // جلب بيانات العمال من الباك إند (Laravel)
  useEffect(() => {
    fetchWorkers();
  }, []);

  const fetchWorkers = async () => {
    try {
      setLoading(true);
      // تغيير الرابط هنا ليتوافق مع السيرفر المحلي الخاص بك
      const response = await fetch('http://127.0.0.1:8000/api/workers');
      const data = await response.json();
      
      // تهيئة العمال بحالة عدم الحضور افتراضياً
      const formattedWorkers = data.map(worker => ({
        ...worker,
        status: 'pending' // pending, present, absent
      }));
      
      setWorkers(formattedWorkers);
    } catch (error) {
      console.error('Error fetching workers:', error);
      alert('لم نتمكن من الاتصال بقاعدة البيانات. تأكد من تشغيل Laravel Server.');
    } finally {
      setLoading(false);
    }
  };

  const toggleStatus = (id) => {
    setWorkers(workers.map(w => {
      if (w.id === id) {
        return { ...w, status: w.status === 'present' ? 'absent' : 'present' };
      }
      return w;
    }));
  };

  const getStatusColor = (status) => {
    switch(status) {
      case 'present': return '#10b981';
      case 'absent': return '#ef4444';
      default: return '#6b7280';
    }
  };

  const renderItem = ({ item }) => (
    <View style={styles.card}>
      <View style={styles.workerInfo}>
        <Text style={styles.workerName}>{item.full_name}</Text>
        <Text style={styles.workerSpec}>{item.specialty} - {item.hourly_rate} درهم/ساعة</Text>
      </View>
      <TouchableOpacity 
        style={[styles.statusButton, { backgroundColor: getStatusColor(item.status) }]}
        onPress={() => toggleStatus(item.id)}
      >
        <Text style={styles.statusText}>
          {item.status === 'present' ? 'حاضر' : item.status === 'absent' ? 'غائب' : 'لم يسجل'}
        </Text>
      </TouchableOpacity>
    </View>
  );

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.title}>لوحة مسؤولة الحضور 📋</Text>
        <Text style={styles.subtitle}>الشركة الحديثة للخياطة</Text>
      </View>
      
      <View style={styles.listContainer}>
        {loading ? (
          <ActivityIndicator size="large" color="#3b82f6" style={{marginTop: 50}} />
        ) : workers.length === 0 ? (
          <Text style={{textAlign: 'center', marginTop: 50, fontSize: 18, color: '#6b7280'}}>لا يوجد عمال مضافون في قاعدة البيانات بعد.</Text>
        ) : (
          <FlatList
            data={workers}
            keyExtractor={item => item.id.toString()}
            renderItem={renderItem}
            contentContainerStyle={{ paddingBottom: 20 }}
          />
        )}
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f3f4f6',
  },
  header: {
    backgroundColor: '#3b82f6',
    padding: 20,
    paddingTop: Platform.OS === 'android' ? 40 : 20,
    alignItems: 'center',
    borderBottomLeftRadius: 30,
    borderBottomRightRadius: 30,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.1,
    shadowRadius: 10,
    elevation: 5,
  },
  title: {
    fontSize: 24,
    color: '#ffffff',
    fontWeight: 'bold',
  },
  subtitle: {
    fontSize: 16,
    color: '#e0e7ff',
    marginTop: 5,
  },
  listContainer: {
    flex: 1,
    padding: 20,
  },
  card: {
    flexDirection: 'row-reverse',
    backgroundColor: '#ffffff',
    padding: 20,
    borderRadius: 15,
    marginBottom: 15,
    alignItems: 'center',
    justifyContent: 'space-between',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 5,
    elevation: 3,
  },
  workerInfo: {
    alignItems: 'flex-end',
  },
  workerName: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#1f2937',
  },
  workerSpec: {
    fontSize: 14,
    color: '#6b7280',
    marginTop: 4,
  },
  statusButton: {
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 25,
  },
  statusText: {
    color: '#ffffff',
    fontWeight: 'bold',
    fontSize: 16,
  }
});
