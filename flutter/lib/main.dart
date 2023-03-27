import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'my_app.dart';
import 'provider/user_provider.dart';

void main() {
  runApp(MultiProvider(providers: [
    ChangeNotifierProvider(create: (context) => UserProvider()),
  ], child: const MyApp()));
}
