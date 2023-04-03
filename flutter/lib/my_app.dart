import 'package:flutter/material.dart';
import 'screen/home_screen.dart';

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'LP1 CIASIE - Atelier 2',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: const HomePage(events: [], ),
    );
  }
}
