import 'package:flutter/material.dart';
import '../class/user.dart';

class UserProvider extends ChangeNotifier {
  User _user =
      User(id: '', email: '', role: '', accessToken: '', refreshToken: '');

  User get user => _user;
  bool get isLogged => _user.id != '';

  void setUser(User user) {
    _user = user;
    notifyListeners();
  }
}
